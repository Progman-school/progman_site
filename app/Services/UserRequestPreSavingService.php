<?php

namespace App\Services;

use App\Exceptions\UserAlert;
use App\sdks\EmailServiceSdk;
use Illuminate\Http\Request;
use App\Models\Request as UserRequest;

class UserRequestPreSavingService
{
    const CONFIRM_URL_PARAM = "confirm";

    public static function addRequest(Request $request): array {
        $score = self::calculateTestScore($request->toArray());
        $descriptionText = self::testScoreDescription($score);
        $userRequest = new UserRequest();
        $userRequest->test_score = $score;
        $userRequest->application_data = json_encode($request->input(), JSON_UNESCAPED_UNICODE);
        $userRequest->type = $request->uid_type;
        $userRequest->hash = self::getRequestHash($request);
        $userRequest->save();
        $userRequest->id = $userRequest->getKey();
        $inject = [
            'test_result_score' => [
                TagService::EN_LANGUAGE => $score,
                TagService::RU_LANGUAGE => $score,
                'timeStamp' => 0,
            ],
            'test_result_description' => [
                TagService::EN_LANGUAGE => $descriptionText,
                TagService::RU_LANGUAGE => $descriptionText,
                'timeStamp' => 0,
            ],
        ];

        if ($userRequest->type == "telegram") {
            $hashLink = "tg://resolve?domain=" . config("services.telegram.bot_login") . "&" . self::CONFIRM_URL_PARAM . "={$userRequest->type}'-'{$userRequest->hash}";
        } elseif ($userRequest->type == "email") {
            $hashLink = "https://{$_SERVER['HTTP_HOST']}/" . EmailServiceSdk::API_ENTRYPOINT . "?" . self::CONFIRM_URL_PARAM . "={$userRequest->type}'-'{$userRequest->hash}";
        }
        return [
            'hash_link' => $hashLink,
            'alert_text' => TagService::getTagValueByName(
                'test_passed_alert_text',
                $request->timeStamp ?? 0,
                $inject
            )[TagService::getCurrentLanguage()],
            'score' => $score,
            'description' => $descriptionText,
        ];
    }

    protected static function testScoreDescription(int $scorePoints):string {
        if ($scorePoints < 20) {
            $descriptionText = '{{bad_test_description}}';
        }
        elseif ($scorePoints <= 35) {
            $descriptionText = '{{satisfactory_test_description}}';
        }
        elseif ($scorePoints <= 59) {
            $descriptionText = '{{good_test_description}}';
        }
        elseif ($scorePoints <= 79) {
            $descriptionText = '{{great_test_description}}';
        }
        else {
            $descriptionText = '{{best_test_description}}';
        }
        return $descriptionText;
    }

    protected static function calculateTestScore(array $testData): int{
        unset($testData['city'], $testData['sex']);
        $score = 10;
        $maxScore = 35;
        foreach ($testData as $keyItem => $testItem) {
            if ($keyItem == 'age') {
                if ($testItem > 14 && $testItem <= 17) {
                    $score += 2;
                }
                elseif ($testItem > 17 && $testItem <= 24) {
                    $score += 1;
                }
                elseif ($testItem > 24 && $testItem <= 35) {
                    $score += 4;
                }
                elseif ($testItem > 35) {
                    $score += 3;
                }
                continue;
            }
            if ($keyItem == 'project_idea') {
                $wordsCount = count(explode(' ', $testItem));
                if ($wordsCount <= 5) {
                    $score += 1;
                }
                elseif ($wordsCount < 35) {
                    $score += 5;
                }
                elseif ($wordsCount > 35) {
                    $score += 3;
                }
            }
        }

        return round($score/$maxScore*100, 1);
    }

    protected static function getRequestHash(Request $request): string {
        return md5(
            UserRequest::all()->count() - 1 . '-' . print_r($request->toArray(), 1). '=' . microtime()
        );
    }
}
