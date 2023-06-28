<?php

namespace App\Http\Controllers;

use App\Models\Request;
use App\Models\Tag;
use App\Models\TagValue;
use App\Models\Uids\Telegram;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use PDO;
use Throwable;


/**
 * TMP code for redirect db data from old db to new structure
 * FOR THE CONTENT TAG'S PART
 */
class DBRebuilder extends MainController
{
    const OLD_DB_HOST = "mysql";
    const OLD_DB_PORT = "3306";
    const OLD_DB_NAME = "laravel_tmp";
    const OLD_DB_USER = "laravel";
    const OLD_DB_PASSWORD = "pass";

    const OLD_DB_DSN = 'mysql:host=' . self::OLD_DB_HOST . ';dbname=' . self::OLD_DB_NAME;

    const FEATURED_CURRENTLY_EMPTY_VALUE = "none";

    public function oldDBConnection():PDO {
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        return new PDO(
            self::OLD_DB_DSN,
            self::OLD_DB_USER,
            self::OLD_DB_PASSWORD,
            $options
        );
    }

    public function rebuildAllFromRequests():string {
        $timeStart = microtime(true);
        $oldDB = $this->oldDBConnection();
        $oldDB->beginTransaction();
        $oldDBRequest = $oldDB->query("
            SELECT r.*, u.id AS 'user_id' FROM requests r
            RIGHT JOIN users u on u.reg_hash = r.hash"
        );
        $oldDBBData = $oldDBRequest->fetchAll(PDO::FETCH_ASSOC);

        DB::beginTransaction();
        try {
            $count = 0;
            foreach ($oldDBBData as $oneRequest) {
                if ($oneRequest->user_id) {
                    $oldUser = $oldDB->query("SELECT * FROM users WHERE id = '{$oneRequest->user_id}';");
                    $oldUserData = $oldUser->fetchAll(PDO::FETCH_ASSOC)[0];

                }
                /** @var Request $reuest */
                $reuest = Request::create([
                    'created_at' => $oneRequest["created_at"],
                    'updated_at' => $oneRequest["updated_at"],
                    'uid' => $oldUserData["tg_id"],
                    'type' => "telegram",
                    'hash' => $oneRequest["hash"],
                    'application_data' => $oldUserData["data"],
                ]);

                /** @var Telegram $telegramUid */
                $telegramUid = Telegram::create([
                    'service_uid' => $oldUser["tg_id"],
                    'service_login' => $oldUser["tg_name"],
                    'data' => $oldUser["user_data"]
                ]);

                /** @var User $user */
                $user = User::create([
                    'first_name' => $oldUser["first_name"],
                    'last_name' => $oldUser["last_name"],
                    'real_first_name' => $oldUser["real_first_name"],
                    'real_last_name' => $oldUser["real_last_name"],
                    'real_middle_name' => $oldUser["real_middle_name"],
                    'telegram' => $telegramUid->id,
                    'email' => null,
                    'status' => 'processed'
                ]);


                $count ++;
            }
        } catch (Throwable $e) {
            DB::rollBack();
            return self::do([
                "result" => "Fail job",
                "count" => $count,
                "time" => (microtime() - $timeStart) / 1000,
                "error" => $e->getMessage(),
                "trace" => $e->getTrace(),
            ]);
        }

        $oldDB->commit();
        DB::commit();


        return self::do([
            "result" => "Success job",
            "count" => $count,
            "time" => round((microtime(true) - $timeStart) * 1000, 3)
        ]);
    }

    /**
    * FOR THE CONTENT TAG'S PART
    */
    public function rebuildTags():string {
        $oldDB = $this->oldDBConnection();
        $oldDB->beginTransaction();
        $oldDBRequest = $oldDB->query("SELECT * FROM `language_contents`");
        $oldDBBData = $oldDBRequest->fetchAll(PDO::FETCH_ASSOC);

        DB::beginTransaction();
        try {
            $count = 0;
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
                $count ++;
            }
        } catch (Throwable $e) {
            DB::rollBack();
            return self::do([
                "result" => "Fail job",
                "count" => $count,
                "error" => $e->getMessage(),
                "trace" => $e->getTrace()
            ]);
        }

        $oldDB->commit();
        DB::commit();

        return self::do([
            "result" => "Success job",
            "count" => $count
        ]);
    }
}
