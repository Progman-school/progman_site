<?php

namespace App\Services;
use App\Exceptions\UserAlert;
use App\Models\Request as UserRequest;
use App\Models\User;
use App\sdks\TelegramBotApiSdk;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class TelegramRequestService extends TelegramBotApiSdk
{
    const KEYBOARD_CHECK_REQUESTS = [
        ['pending processing' => 'request_await'],
        ['DONE' => 'request_complete']
    ];

    const KEYBOARD_FOR_REPEATER_REQUEST = ['Resend the request' => 'repeat_request'];

    /**
     * @throws UserAlert
     */
    public function manageEntryCommand(): bool
    {
        // tg://resolve?domain=progManTest_bot&start=telegram-720b35bf8923713e5bbbdf1c50a7eb0b
        try {
            $message = $this->request->get("message");
            $command_params = trim(substr($message["text"], $message["entities"][0]["length"]));
            Log::notice("command: " . substr($message["text"], 0, $message["entities"][0]["length"]) . " |   hash: $command_params \n");

            switch (substr($message["text"], 0, $message["entities"][0]["length"])) {  // check command name
                case '/start':
                    $this->confirmRequest($this->request, $command_params);
                    break;
                default:
                    return false;
            }
        } catch (UserAlert $e) {
            $this->sendEchoMessage($e->getMessage());
        } catch (Throwable $e) {
            $this->sendEchoMessage("Error! Unexpected command issue.");
            Log::debug($e);
        }
        return true;
    }

    public function manageEntryMessage(): bool {
        switch ($this->request->get("message")["text"]) {
            case "how yes":
                $this->sendEchoMessage("how yes!");
                break;
            case "how no":
                $this->sendEchoMessage("how no!");
                break;
            case "how all":
                $this->sendEchoMessage("how all!");
                break;
            default:
               return false;

        }
        return true;
    }

    public function manageEntryCallbackQuery(): bool {
        switch ($this->request->get("callback_query")["data"]) {
            case self::KEYBOARD_CHECK_REQUESTS[0]:
                $this->sendEchoMessage("cb button");
                break;
            default:
               return false;
        }
        return true;
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
            $this->sendEchoMessage($userMessage, self::KEYBOARD_FOR_REPEATER_REQUEST);
        } else {
            $this->sendNewRequestToAdminChat($confirmedUser, $userRequest, $request, $userRequestsCount);

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

    public function sendNewRequestToAdminChat(
        User $confirmedUser,
        UserRequest $userRequest,
        Request $request,
        int $userRequestsCount
    ): void {
        $result = $this->sendMessageToAdminChat($this->prepareNotesMessage(
            $confirmedUser,
            $userRequest,
            $request,
            $userRequestsCount
        ), self::KEYBOARD_CHECK_REQUESTS[0]);
//        if ($result[""])
//        $userRequest
    }

    public function prepareNotesMessage(User $user, UserRequest $userRequest, Request $request, $requestsCount): string
    {
        $userName = $request["message"]["from"]["username"] ?? " - ";

        $message = "New request!\n";
        if ($requestsCount > 1) {
            $message .= "REPEATER: {$requestsCount}\n";
            $message .= "first request: " . date("d.m.Y", strtotime($user->created_at)) . "\n\n";
        }
        $message .= "Telegram: " . ($userName ? "@{$userName}" : " - ")  . "\n" .
            "Name: {$user->first_name} {$user->last_name}\n" .
            "tg id: {$request["message"]["from"]["id"]}\n" .
            "system id: {$userRequest->id}\n\n";
        $message .= "TEST: \n";
        foreach (json_decode($userRequest->application_data) as $keyTestItem => $testItem) {
            if ($keyTestItem == "uid_type") {
                continue;
            }
            $message .= "$keyTestItem: $testItem\n";
        }
        $message .= "TEST SCORE: {$userRequest->test_score}\n";
        return $message;
    }
}
