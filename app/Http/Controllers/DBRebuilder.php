<?php

namespace App\Http\Controllers;

use App\Helpers\APIHelper;
use App\Models\Certificate;
use App\Models\Course;
use App\Models\Request;
use App\Models\Tag;
use App\Models\TagValue;
use App\Models\Technology;
use App\Models\Uids\Telegram;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Exception;
use PDO;
use Throwable;


/**
 * TMP code for redirect db data from old db to new structure
 * Only for one time using
 * that's why all the code is here except only routs.
 *
 * IT'll suppose to be deleted from the Dev and Main branches
 * after lunching new version project
 */
class DBRebuilder extends MainController
{
    const DEF_OLD_DB_HOST = "mysql";
    const OLD_DB_PORT = "3306";
    const OLD_DB_NAME = "u0435463_progman_site";
    const DEF_OLD_DB_USER = "laravel";
    const DEF_OLD_DB_PASSWORD = "pass";

    private static PDO $oldConnection;

    public function __construct()
    {
        self::$oldConnection = $this->oldDBConnection();
    }

    public function oldDBConnection():PDO {
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        return new PDO(
            'mysql:host=' . env("OLD_DB_HOST",self::DEF_OLD_DB_HOST) . ';dbname=' . self::OLD_DB_NAME,
            env("OLD_DB_USER",self::DEF_OLD_DB_USER),
            env("OLD_DB_PASSWORD",self::DEF_OLD_DB_PASSWORD),
            $options
        );
    }

    /**
     * @throws Exception
     */
    private static function lunchFacade(callable $lunchMethod):array {
        $timeStart = microtime(true);
        self::$oldConnection->beginTransaction();
        DB::beginTransaction();
        try {
            $count = null;
            $lunchMethod($count);

            self::$oldConnection->commit();
            DB::commit();

            return [
                "result" => "Success job",
                "count" => $count,
                "time" => round((microtime(true) - $timeStart) * 1000, 3),
            ];
        }catch (Throwable $e) {
            DB::rollBack();
            self::$oldConnection->rollBack();
            throw new Exception(APIHelper::createFrontAnswer(([
                "result" => "Fail job",
                "count" => $count,
                "time" => round((microtime(true) - $timeStart) * 1000, 3),
                "error" => $e->getMessage(),
                "trace" => $e->getTrace(),
            ]));
        }
    }

    /**
     * @throws Exception
     */
    public function rebuildCourses():array {
        return self::lunchFacade(function(&$count) {

            $oldCourses = self::$oldConnection->query("SELECT * FROM courses;")
                ->fetchAll(PDO::FETCH_ASSOC);
            foreach ($oldCourses as $oneCourse) {
                $oldTechnologiesOneCourse = self::$oldConnection->query(
                    "SELECT * FROM technologies_by_courses WHERE course = '{$oneCourse['id']}';"
                )->fetchAll(PDO::FETCH_ASSOC);
                $technologiesIds = [];
                foreach ($oldTechnologiesOneCourse as $oldOneCourseTechnologiesRecord) {
                    $oneCourseOldTechnology = self::$oldConnection->query(
                        "SELECT * FROM technologies WHERE id = '{$oldOneCourseTechnologiesRecord['technology']}';"
                    )->fetchAll(PDO::FETCH_ASSOC)[0];
                    /** @var Technology $technology */
                    $technology = Technology::firstOrCreate([
                        'name' => $oneCourseOldTechnology["name"],
                        'type' => $oneCourseOldTechnology["type"],
                        'description' => $oneCourseOldTechnology["description"],
                    ]);
                    $technologiesIds[] = $technology->id;
                }
                /** @var Course $course */
                $course = Course::create([
                    'name' => $oneCourse["name"],
                    'level' => $oneCourse["level"],
                    'type' => $oneCourse["type"],
                ]);
                $course->technologies()->attach($technologiesIds);
                $count ++;
            }

        });
    }

    /**
     * @throws Exception
     */
    public function rebuildCertificates():array {
        return self::lunchFacade(function(&$count) {
            $oldCertificates = self::$oldConnection->query("
                SELECT c.*, u.tg_id AS 'utg_id' FROM certificates c
                INNER JOIN users u ON c.user = u.id;"
            )->fetchAll(PDO::FETCH_ASSOC);
            foreach ($oldCertificates as $oldCertificate) {
                $newTechnologyIds = [];
                $OldTechnologiesIds = self::$oldConnection->query("
                        SELECT * FROM technologies_by_certificates WHERE certificate = '{$oldCertificate["id"]}';
                ")->fetchAll(PDO::FETCH_ASSOC);

                foreach ($OldTechnologiesIds as $oldTechnologiesId) {
                    $OldTechnology = self::$oldConnection->query("
                        SELECT * FROM technologies WHERE id = '{$oldTechnologiesId["technology"]}';
                    ")->fetchAll(PDO::FETCH_ASSOC)[0];
                    $newTechnologyIds[] = Technology::where("name", $OldTechnology["name"])->first()->id;
                }
                /** @var User $user */
                $user = User::whereRelation("telegrams", "service_uid", $oldCertificate["utg_id"])->first();

                $oldCourse = self::$oldConnection->query(
                    "SELECT * FROM courses WHERE id = '{$oldCertificate["course"]}';"
                )->fetchAll(PDO::FETCH_ASSOC)[0];

                /** @var Course $course */
                $course = Course::where("name", $oldCourse["name"])->first();

                /** @var Certificate $certificate */
                $certificate = Certificate::create([
                    'full_number' => $oldCertificate["full_number"],
                    'user_id' => $user->id,
                    'course_id' => $course->id,
                    'start_date' => $oldCertificate["start_date"],
                    'date' => $oldCertificate["date"],
                    'hours' => $oldCertificate["hours"],
                    'description' => $oldCertificate["description"],
                    'language' => $oldCertificate["language"],
                    'blank' => $oldCertificate["blank"],
                ]);
                $certificate->technologies()->attach($newTechnologyIds);
                $count ++;
            }
        });
    }

    /**
     * @throws Exception
     */
    public function rebuildUsersAndRequests():array {
        return self::lunchFacade(function(&$count) {
            $oldRequests = self::$oldConnection->query("
                SELECT r.*, u.id AS 'user_id' FROM requests r
                RIGHT JOIN users u on u.reg_hash = r.hash"
            )->fetchAll(PDO::FETCH_ASSOC);
            foreach ($oldRequests as $oneRequest) {
                if (!isset($oneRequest["user_id"]) || !$oneRequest["user_id"]) {
                    continue;
                }
                $oldUser = self::$oldConnection->query(
                    "SELECT * FROM users WHERE id = '{$oneRequest["user_id"]}';"
                )->fetchAll(PDO::FETCH_ASSOC)[0];
                /** @var Request $reuest */
                $reuest = Request::create([
                    'created_at' => $oneRequest["created_at"],
                    'updated_at' => $oneRequest["updated_at"],
                    'uid' => $oldUser["tg_id"],
                    'type' => "telegram",
                    'hash' => $oneRequest["hash"],
                    'application_data' => $oneRequest["data"],
                ]);

                /** @var User $user */
                $user = User::create([
                    'created_at' => $oldUser["created_at"],
                    'updated_at' => $oldUser["updated_at"],
                    'first_name' => $oldUser["first_name"],
                    'last_name' => $oldUser["last_name"],
                    'real_first_name' => $oldUser["real_first_name"],
                    'real_last_name' => $oldUser["real_last_name"],
                    'real_middle_name' => $oldUser["real_middle_name"],
                    'status' => 'processed'
                ]);

                Telegram::create([
                    'service_uid' => $oldUser["tg_id"],
                    'service_login' => $oldUser["tg_name"],
                    'data' => $oldUser["user_data"],
                    'user_id' => $user->id,
                ]);

                $user->requests()->attach($reuest->id);

                $count ++;
            }
        });
    }

    /**
     * FOR THE CONTENT TAG'S PART
     * @throws Exception
     */
    public function rebuildTags():array {
        return self::lunchFacade(function(&$count) {
            $oldDBRequest = self::$oldConnection->query("SELECT * FROM `language_contents`");
            $oldDBBData = $oldDBRequest->fetchAll(PDO::FETCH_ASSOC);
            foreach ($oldDBBData as $oneRow) {
                $tagValueIDs = [];
                /** @var Tag $tag */
                $tag = Tag::create([
                    'name' => $oneRow["tag"],
                    'type' => $oneRow["type"],
                    'description' => $oneRow["name"],
                    'show' => $oneRow["show"],
                    'order' => $oneRow["order"],
                ]);
                if (@$oneRow["en_lang"]) {
                    /** @var TagValue $tagValue */
                    $tagValue = TagValue::create([
                        "content" => "en",
                        "value" => $oneRow["en_lang"],
                    ]);
                    $tagValueIDs[] = $tagValue->id;
                }
                if (@$oneRow["ru_lang"]) {
                    /** @var TagValue $tagValue */
                    $tagValue = TagValue::create([
                        "content" => "ru",
                        "value" => $oneRow["ru_lang"],
                    ]);
                    $tagValueIDs[] = $tagValue->id;
                }
                if (@$oneRow["file_url"]) {
                    /** @var TagValue $tagValue */
                    $tagValue = TagValue::create([
                        "content" => "url",
                        "value" => $oneRow["file_url"],
                    ]);
                    $tagValueIDs[] = $tagValue->id;
                }
                $tag->tagValues()->attach($tagValueIDs);
                $count++;
            }
        });
    }

    /**
     * @throws Exception
     */
    public function rebuildAll(): string {
        $results = [];
        $results["tags"] = $this->rebuildTags();
        $results["courses"] = $this->rebuildCourses();
        $results["users_requests"] = $this->rebuildUsersAndRequests();
        $results["certificates"] = $this->rebuildCertificates();

        $time = null;
        $items = null;
        foreach ($results as $result) {
            $time += $result["time"];
            $items += $result["count"];
        }
        $results["total"]["items"] = $items;
        $results["total"]["time"] = $time;

        return APIHelper::createFrontAnswer(($results);
    }
}
