<?php

namespace App\Services;

use App\Exceptions\UserAlert;
use Illuminate\Http\Request;
use App\Models\Request as UserRequest;

class UserRequestPreSavingService
{
    public static function addRequest(Request $request): array {
        $score = self::calculateTestScore($request->toArray());
        $descriptionText = self::testScoreDescription($score);
        $userRequest = new UserRequest();
        $userRequest->test_score = $score;
        $userRequest->application_data = json_encode($request->input(), JSON_UNESCAPED_UNICODE);
        $userRequest->type = $request->uid_type;
        $userRequest->save();
        $userRequest->id = $userRequest->getKey();
        $userRequest->hash = self::getRequestHash($userRequest->id);
        $userRequest->save();
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
        return [
            'hash' => $userRequest->type . '-' . $userRequest->hash,
            'alert_text' => TagService::getTagValueByName(
                'test_passed_alert_text',
                $request->timeStamp ?? 0,
                $inject
            ),
            'score' => $score,
            'description' => $descriptionText,
        ];
    }

    protected static function testScoreDescription(int $scorePoints):string {
        if ($scorePoints <= 39) {
            $descriptionText = '{{best_test_description}}';
        } elseif ($scorePoints > 39 && $scorePoints <= 59) {
            $descriptionText = '{{great_test_description}}';

        } elseif ($scorePoints > 59 && $scorePoints <= 79) {
            $descriptionText = '{{good_test_description}}';
        } elseif ($scorePoints > 79) {
            $descriptionText = '{{satisfactory_test_description}}';
        } else {
            $descriptionText = '{{bad_test_description}}';
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

    protected static function getRequestHash(UserRequest $userRequest): ?string {
        if (empty($userRequest->data) || is_null($userRequest->id)) {
            throw new UserAlert("Upps! Something is wrong with application #{$userRequest->id}. Please, contact us, and we'll solve the problem.");
        }
        return md5($userRequest->id . '-' . print_r($userRequest->data, 1). '=' . microtime());
    }
}
