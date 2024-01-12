<?php

namespace App\Services;

use App\Exceptions\UserAlert;
use App\sdks\EmailServiceSdk;
use App\sdks\TelegramBotApiSdk;
use Exception;
use Illuminate\Http\Request;

class EmailRequestService extends EmailServiceSdk
{
    protected TelegramRequestService $telegramService;

    public function __construct(Request $request) {
        parent::__construct($request);
        $this->telegramService = new TelegramRequestService($request);
    }

    /**
     * @throws UserAlert
     * @throws Exception
     */
    public function confirmRequest(): string
    {
        $confirmationHash = $this->request->hash;
        $userRequest = UserService::confirmUserRequest($confirmationHash);
        if ($userRequest->type == 'email') {
            $userRequest->uid = json_decode($userRequest->application_data)->email;
            $userRequest->save();
        }
        $confirmedUser = UserService::addOrGetUserByRequest($this->request, $userRequest);
        $userRequestsCount = UserService::getCountOfUserRequests($confirmedUser);
        if ($userRequestsCount > 1) {
            $userMessage = TagService::getTagValueByName("previously_applied_warning_message")[TagService::getCurrentLanguage()]
                . "\n\n" . TagService::getTagValueByName("telegram_question_to_repeater")[TagService::getCurrentLanguage()];
        } else {
            $this->telegramService->sendNewRequestToAdminChat($confirmedUser, $userRequest, $this->request, $userRequestsCount);

            $userMessage = TagService::getTagValueByName(
                    "thanks_for_registration_message",
                    0 ,
                    ["new_id" => [
                        TagService::DEFAULT_LANGUAGE => $confirmedUser->id]
                    ]
                )[$this->request->lang ?? TagService::getCurrentLanguage()]
                . "\n\n" . TagService::getTagValueByName(
                    "telegram_success_answer_to_new_user"
                )[$this->request->lang ?? TagService::getCurrentLanguage()];
        }
        return $userMessage;
    }

    public function showResultPage(string $text): string {
        return view(
            'api_answers.result_page_of_email_action',
            ['text' => str_replace("\n", "<br/>", $text)]
        )->render();
    }
}
