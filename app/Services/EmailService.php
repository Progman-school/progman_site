<?php

namespace App\Services;

use App\Exceptions\UserAlert;
use App\Models\Request as UserRequest;
use App\sdks\EmailServiceSdk;
use App\sdks\TelegramBotApiSdk;
use Exception;
use Illuminate\Http\Request;

class EmailService extends EmailServiceSdk
{
    protected TelegramService $telegramService;

    public function __construct(Request $request) {
        parent::__construct($request);
        $this->telegramService = new TelegramService($request);
    }

    /**
     * @throws UserAlert
     * @throws Exception
     */
    public function confirmRequest(): string
    {
        $confirmationHash = $this->request->{UserRequestService::CONFIRM_URL_PARAM};

        $userRequest = UserRequestService::confirmUserRequest($confirmationHash);
        $userName = explode(" ", $userRequest->name);
        $confirmedUser = UserService::addOrGetUserByRequest(
            $userRequest,
            $userName[0] ?? null,
            $userName[1] ?? null
        );

        UidService::createUid(
            $userRequest->type,
            $this->request["contact"],
            $this->request["contact"],
            $confirmedUser->id,
            json_encode($this->request->toArray(), JSON_UNESCAPED_UNICODE)
        );

        $applicationDataArray = json_decode($userRequest->application_data, true);
        $confirmedData = [];
        $confirmedData["Email"] = $this->request["contact"];
        $confirmedData["First name"] = $userName[0] ?? "-";
        $confirmedUser["Last name"] = $userName[1] ?? "-";

        $result = $this->telegramService->editMessageInAdminChat(UserRequestService::createRequestMessageForAdminChat(
            $userRequest,
            $confirmedData,
            array_diff_key($applicationDataArray, array_flip(["uid_type", "name", "contact"])),
            $userRequest->getRepeatsCount(),
            $userRequest->getRepeatsCount()
        ),
            $userRequest->admin_message_id,
            TelegramService::REQUEST_STATUS_KEYBOARDS[UserRequest::CONFIRMED_STATUS]
        );

        $userRequest->admin_message_id = $result["result"]["message_id"];
        $userRequest->save();

        $userMessage = TagService::getTagValueByName(
                "thanks_for_registration_message",
                0 ,
                ["new_id" => [
                    TagService::DEFAULT_LANGUAGE => $confirmedUser->id]
                ]
            )[TagService::getCurrentLanguage()]
            . "\n\n" . TagService::getTagValueByName(
                "telegram_success_answer_to_new_user"
            )[TagService::getCurrentLanguage()];

        return $userMessage;
    }

    public function showResultPage(string $text): string {
        return view(
            'api_answers.result_page_of_email_action',
            ['text' => str_replace("\n", "<br/>", $text)]
        )->render();
    }
}
