<?php

namespace App\Services;

use App\Exceptions\UserAlert;
use App\Mail\ConfirmApplication;
use App\sdks\EmailServiceSdk;
use Illuminate\Http\Request;
use App\Models\Request as UserRequest;
use Illuminate\Support\Facades\Mail;

class UserRequestPreSavingService
{
    const CONFIRM_URL_PARAM = "hash";

    public static function addRequest(Request $request): ?array {
        $score = self::calculateTestScore($request->toArray());
        $userRequest = new UserRequest();
        $userRequest->test_score = $score;
        $userRequest->application_data = json_encode($request->input(), JSON_UNESCAPED_UNICODE);
        $userRequest->type = $request->uid_type;
        $userRequest->hash = self::getRequestHash($request);
        $userRequest->save();
        $userRequest->id = $userRequest->getKey();

        $return = [
            'hash_link' => null,
            'alert_text' => TagService::getTagValueByName(
                'alert_text_after_application_complete',
                $request->timeStamp ?? 0,
                [
                    'type_of_user_uid' => [
                        TagService::EN_LANGUAGE => $request->uid_type,
                        TagService::RU_LANGUAGE => $request->uid_type,
                    ],
                ]
            )[TagService::getCurrentLanguage()],
        ];

        if ($userRequest->type == "telegram") {
                $return['hash_link'] = "tg://resolve?domain=" . config("services.telegram.bot_login") .
                    "&" . self::CONFIRM_URL_PARAM . "={$userRequest->type}'-'{$userRequest->hash}";
        } elseif ($userRequest->type == "email") {
            Mail::to($request->email)->send(new ConfirmApplication(
                "https://{$_SERVER['HTTP_HOST']}/" . EmailServiceSdk::API_ROUT . EmailServiceSdk::API_ENTRYPOINT .
                "?" . http_build_query([
                    "action" => "confirm_request",
                    self::CONFIRM_URL_PARAM => "{$userRequest->type}-{$userRequest->hash}",
                    "lang" => TagService::getCurrentLanguage(),
                ]),
                $score,
                self::generateAlertText($userRequest)
            ));
        }
        return $return;
    }

    public static function generateAlertText(UserRequest $userRequest): string {
        $descriptionText = self::testScoreDescription($userRequest->test_score);

        return TagService::getTagValueByName(
            'test_passed_alert_text',
            $userRequest->created_at->getTimestamp(),
            [
                'test_result_score' => [
                    TagService::EN_LANGUAGE => $userRequest->test_score,
                    TagService::RU_LANGUAGE => $userRequest->test_score,
                    'timeStamp' => 0,
                ],
                'test_result_description' => [
                    TagService::EN_LANGUAGE => $descriptionText,
                    TagService::RU_LANGUAGE => $descriptionText,
                    'timeStamp' => 0,
                ],
            ]
        )[TagService::getCurrentLanguage()];
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
