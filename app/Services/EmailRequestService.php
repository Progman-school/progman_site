<?php

namespace App\Services;

use App\Exceptions\UserAlert;
use App\sdks\EmailServiceSdk;
use App\sdks\TelegramBotApiSdk;
use Exception;
use Illuminate\Http\Request;

class EmailRequestService extends EmailServiceSdk
{
    protected TelegramBotApiSdk $telegramSdk;

    public function __construct(Request $request) {
        parent::__construct($request);
        $this->telegramSdk = new TelegramBotApiSdk($request);
    }

    /**
     * @throws UserAlert
     * @throws Exception
     */
    public function confirmRequest(Request $request, string $confirmationHash): void
    {
        $userRequest = UserService::confirmUserRequest($confirmationHash);
        $confirmedUser = UserService::addOrGetUserByRequest($request, $userRequest);
        $userRequestsCount = UserService::getCountOfUserRequests($confirmedUser);
        if ($userRequestsCount > 1) {
            $userMessage = TagService::getTagValueByName("previously_applied_warning_message")[TagService::getCurrentLanguage()]
                . "\n\n" . TagService::getTagValueByName("telegram_question_to_repeater")[TagService::getCurrentLanguage()];
            $this->showResultPage($userMessage);
        } else {
            $this->telegramSdk->sendNewRequestToAdminChat($confirmedUser, $userRequest, $request, $userRequestsCount);

            $userMessage = TagService::getTagValueByName(
                    "thanks_for_registration_message",
                    0 ,
                    ["new_id" => [
                        TagService::DEFAULT_LANGUAGE => $confirmedUser->id]
                    ]
                )[TagService::getCurrentLanguage()]
                . "\n\n" . TagService::getTagValueByName(
                    "telegram_success_answer_to_new_user",
                    0,
                    ["telegram_admit_account" =>
                        [TagService::DEFAULT_LANGUAGE => config("services.telegram.contact_manager_login")]
                    ]
                )[TagService::getCurrentLanguage()];
            $this->sendEchoMessage($userMessage);
        }
    }

    public function showResultPage() {

    }
}
