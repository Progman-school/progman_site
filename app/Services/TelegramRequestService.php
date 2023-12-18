<?php

namespace App\Services;
use App\Exceptions\UserAlert;
use App\Models\Request as UserRequest;
use App\Models\User;
use App\sdks\TelegramBotApiSdk;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
    public function manageEntryCommand(): void {
        $message = $this->request->get("message")->text;
        $offset = $this->request->get("message")->entities->length;
        $command_params = trim(substr($message, $offset));
        $ss = substr($message, 0, $offset);
        Log::alert($ss);
        switch (substr($message, 0, $offset)) {  // check command name
            case '/start':
                exit();
                break;
            case '/confirm':
                $this->confirmRequest($this->request, $command_params);
                break;
            default:
                abort(404);
        }
    }

    public function manageEntryMessage(): void {
        switch ($this->request->get("message")["text"]) {
            case "how yes":
                exit();
                break;
            case "how no":
                exit();
                break;
            case "how all":
                exit();
                break;
            default:
                $this->sendEchoMessage("hi!");
//                abort(404);
        }
    }

    public function manageEntryCallbackQuery(): void {
        switch ($this->request->get("message")["text"]) {
            case "button":
                exit();
                break;
            default:
                $this->sendEchoMessage("hi cb!");
//                abort(404);
        }
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
            $userMessage = TagService::getTagValueByName("previously_applied_warning_message")
                . "\n\n" . TagService::getTagValueByName("telegram_question_to_repeater");
            $this->sendEchoMessage($userMessage, self::KEYBOARD_FOR_REPEATER_REQUEST);
        } else {
            $this->sendMessageToAdminChat($this->prepareNotesMessage(
                $confirmedUser,
                $userRequest,
                $userRequestsCount
            ));
            $userMessage = TagService::getTagValueByName("thanks_for_registration_message")
                . "\n\n" . TagService::getTagValueByName("telegram_success_answer_to_new_user",);
            $this->sendEchoMessage($userMessage);
        }
    }

    public function prepareNotesMessage(User $user, UserRequest $userRequest, int $requestsCount): string
    {
        $message = "New request!\n";
        if ($requestsCount > 1) {
            $message .= "REPEATER: {$requestsCount}\n";
            $message .= "first request: " . date("d.m.Y", strtotime($user->created_at)) . "\n\n";
        }
        $message .= "Telegram: " . ($userRequest->service_uid?"@{$userRequest?->service_login}":" - "). "\n" .
            "Name: {$user->first_name} {$user->last_name}\n" .
            "id: {$userRequest->service_uid}\n\n";
        $message .= "TEST: \n";
        foreach (json_decode($userRequest->data) as $keyTestItem => $testItem) {
            $message .= "$keyTestItem: $testItem\n";
        }
        $message .= "Test score: {$userRequest->test_score}\n";
        return $message;
    }
}
