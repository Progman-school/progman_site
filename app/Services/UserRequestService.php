<?php

namespace App\Services;

use App\Exceptions\UserAlert;
use App\Mail\ConfirmApplication;
use App\sdks\EmailServiceSdk;
use Exception;
use Illuminate\Http\Request;
use App\Models\Request as UserRequest;
use Illuminate\Support\Facades\Mail;

class UserRequestService
{
    const CONFIRM_URL_PARAM = "start";
    const REQUEST_DELAY_DAYS = 3;
    const MAX_REQUEST_REPEATS = 3;

    public static function addRequest(Request $request): ?array {
        if (!in_array($request->uid_type, UidService::AVAILABLE_TYPES)) {
            throw new Exception("Unexpected uid type '{$request->uid_type}'");
        }

        $score = self::calculateTestScore($request->toArray());
        $userRequest = new UserRequest();
        $userRequest->test_score = $score;
        $userRequest->application_data = json_encode($request->input(), JSON_UNESCAPED_UNICODE);
        $userRequest->type = $request->uid_type;
        $userRequest->contact = $request->contact;
        $userRequest->name = $request->name;
        $userRequest->hash = self::getRequestHash($request);
        $userRequest->language = TagService::getCurrentLanguage();
        $userRequest->status = UserRequest::RECEIVED_STATUS;
        $userRequest->save();

        $return = [
            'hash_link' => null,
            'alert_text' => TagService::getTagValueByName(
                'alert_text_after_application_complete',
                $request->timeStamp ?? 0,
                [
                    'type_of_user_uid' => [
                        TagService::DEFAULT_LANGUAGE => $request->uid_type,
                        'timeStamp' => 0,
                    ],
                    'where_to_look_for_results' => [
                        TagService::DEFAULT_LANGUAGE => ucfirst($userRequest->type),
                        'timeStamp' => 0,
                    ],
                ]
            )[TagService::getCurrentLanguage()]
                . "<br /><br />" .
            TagService::getTagValueByName(
                "what_to_do_to_get_offer_by_{$userRequest->type}"
            )[TagService::getCurrentLanguage()],
        ];

        if ($userRequest->type == "telegram") {
                $return['hash_link'] = "tg://resolve?domain=" . config("services.telegram.bot_login") .
                    "&" . self::CONFIRM_URL_PARAM . "={$userRequest->type}-{$userRequest->hash}";
        } elseif ($userRequest->type == "email") {
            Mail::to($request->contact)->send(new ConfirmApplication(
                "https://{$_SERVER['HTTP_HOST']}/" . EmailServiceSdk::API_ROUT . EmailServiceSdk::API_ENTRYPOINT .
                "?" . http_build_query([
                    "action" => "confirm_request",
                    self::CONFIRM_URL_PARAM => "{$userRequest->type}-{$userRequest->hash}",
                ]),
                $score,
                self::generateAlertText($userRequest)
            ));
        }

        $telegramService = new TelegramService();
        $result = $telegramService->sendMessageToAdminChat(
            UserRequestService::createRequestMessageForAdminChat(
                $userRequest,
                array_diff_key($request->toArray(), array_flip(["uid_type", "name", "contact"]))
            ),
            TelegramService::REQUEST_STATUS_KEYBOARDS[$userRequest->status]
        );

        $userRequest->admin_message_id = $result["result"]["message_id"];
        $userRequest->save();

        return $return;
    }

    public static function generateAlertText(UserRequest $userRequest): string {
        $descriptionText = self::testScoreDescription($userRequest->test_score);

        return TagService::getTagValueByName(
            'test_passed_alert_text',
            $userRequest->created_at->getTimestamp(),
            [
                'test_result_score' => [
                    TagService::DEFAULT_LANGUAGE => $userRequest->test_score,
                    'timeStamp' => 0,
                ],
                'test_result_description' => [
                    TagService::DEFAULT_LANGUAGE => $descriptionText,
                    'timeStamp' => 0,
                ],
            ]
        )[TagService::getCurrentLanguage()];
    }

    protected static function testScoreDescription(int $scorePoints):string {
        if ($scorePoints < 25) {
            $descriptionText = '{{bad_test_description}}';
        }
        elseif ($scorePoints <= 35) {
            $descriptionText = '{{satisfactory_test_description}}';
        }
        elseif ($scorePoints <= 49) {
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
        $score = 0.5;
        foreach ($testData as $keyItem => $testItem) {
            if ($keyItem == 'age') {
                if ($testItem > 14 && $testItem <= 17) {
                    $score *= 0.6;
                }
                elseif ($testItem > 17 && $testItem <= 24) {
                    $score *= 1;
                }
                elseif ($testItem > 24 && $testItem <= 35) {
                    $score *= 0.9;
                }
                elseif ($testItem > 35) {
                    $score *= 0.7;
                }
                continue;
            }
            if ($keyItem == "know_html") {
                if (str_starts_with('yes', $testItem)) {
                    $score *= 1;
                }
                else {
                    $score *= 0.8;
                }
                continue;
            }
            if ($keyItem == 'project_idea') {
                $wordsCount = count(explode(' ', $testItem));
                if ($wordsCount <= 5) {
                    $score *= 0.5;
                }
                elseif ($wordsCount < 35) {
                    $score *= 1;
                }
                elseif ($wordsCount > 35) {
                    $score *= 0.9;
                }
            }
            if ($keyItem == 'occupation') {
                $score += ((int) substr($testItem, -1)) / 10;
            }
            if(str_starts_with($keyItem, 'skill_')) {
                $score += ((int) substr($testItem, -1)) / 10;
            }
            if($keyItem == 'target') {
                $score *= (1 + ((int) substr($testItem, -1)) / 10);
            }
            if($keyItem == 'how_doing') {
                $score += ((int) substr($testItem, -1)) / 10;
            }
        }

        return round($score*100, 1);
    }

    protected static function getRequestHash(Request $request): string {
        return md5(
            UserRequest::all()->count() - 1 . '-' . print_r($request->toArray(), 1). '=' . microtime()
        );
    }

    public static function createRequestMessageForAdminChat(
        UserRequest $userRequest,
        array $testData,
        array $confirmedData = null,
        int $requestsCount = null,
        string $firstRequest = null
    ): string
    {
        $message = "Request! ({$userRequest->type} type)\n";
        $message .= "№: {$userRequest->id}\n";
        $message .= "Lang: {$userRequest->language}\n";
        $message .= "Name: {$userRequest->name}\n";
        $message .= "Contact: {$userRequest->contact}\n\n";

        $message .= "Created: " . date("m/d/Y H:i:s", strtotime($userRequest->created_at)) . "\n";
        $message .= "Updated: " . ($userRequest->updated_at ? date("m/d/Y H:i:s", strtotime($userRequest->updated_at)) : "-") . "\n";
        $message .= "Status: {$userRequest->status}\n\n";

        if ($confirmedData) {
            $message .= "CONFIRMED DATA:\n";
            foreach ($confirmedData as $keyContactItem => $contactItem) {
                $message .= "$keyContactItem: $contactItem\n";
            }
            $message .= "\n";
        }

        if ($requestsCount && $requestsCount > 1) {
            $message .= "REPEATER: {$requestsCount}\n\n";
            $message .= "first request: " . ($firstRequest ? date("d.m.Y", strtotime($firstRequest)) : "none") . "\n";
        }

        $message .= "TEST: \n";
        foreach ($testData as $keyTestItem => $testItem) {
            $message .= "$keyTestItem: $testItem\n";
        }
        $message .= "\nTEST SCORE: {$userRequest->test_score}\n";
        return $message;
    }

    /**
     * @throws UserAlert
     * @throws Exception
     */
    public static function confirmUserRequest(string $hashString): UserRequest
    {
        $hashData = explode("-", $hashString);
        $userRequest = UserRequest::where(["type" => $hashData[0], "hash" => $hashData[1]])->first();
        if (!$userRequest?->id) {
            throw new UserAlert(
                TagService::getTagValueByName("invalid_command_from_site_error")[TagService::getCurrentLanguage()]
            );
        }

        TagService::setCurrentLanguage($userRequest->language);
        if ($userRequest->status == UserRequest::CONFIRMED_STATUS) {
            throw new UserAlert(
                TagService::getTagValueByName("warning_request_is_already_confirmed")[TagService::getCurrentLanguage()]
            );
        }

        $userRequest->status = UserRequest::CONFIRMED_STATUS;
        $userRequest->save();
        return $userRequest;
    }
}
