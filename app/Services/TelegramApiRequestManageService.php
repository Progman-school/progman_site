<?php

namespace App\Services;
use Illuminate\Http\Request;
use \App\Models\Request as UserRequest;
use App\Exceptions\UserAlert;
use App\Models\User;

class TelegramApiRequestManageService extends TelegramApiService
{
    const KEYBOARD_CHECK_REQUESTS = [
        ['pending processing' => 'request_await'],
        ['DONE' => 'request_complete']
    ];

    const KEYBOARD_FOR_REPEATER_REQUEST = ['Resend the request' => 'repeat_request'];

    // TODO: Clean everything up here!

    public function manageEntryCommand(Request $request): mixed {
        $message = $request->get("message")->text;
        $offset = $request->get("message")->entities->length;
        $command_params = trim(substr($message, $offset));
        switch (substr($message, 0, $offset)) {  // check command name
            case '/start':
                break;
            case '/confirm':
                $this->confirmRequest($request);
                break;
            default:
                abort(404);
        }

        return json_decode($response, 1) ?? $response;
    }

    public function manageEntryMessage(Request $request): mixed {
        switch ($request->get("message")->get("text")) {
            case mb_stripos($input['message']['text'], '/start ') !== false:

                break;
            case "how yes":
                break;
            case "how no":
                break;
            case "how all":
                break;
            default:
                abort(404);
        }

        return json_decode($response, 1) ?? $response;
    }

    public function manageEntryCallbackQuery(Request $request): mixed {

        return;
    }

    /**
     * @throws UserAlert
     */
    public function confirmRequest(Request $request, string $userRequestHash): void
    {
        $confirmedUser = UserService::confirmUserRequest($request, $userRequestHash);
        $userRequestsCount = UserService::getCountOfUserRequests($confirmedUser);

        if ($userRequestsCount > 1) {
            $userMessage = TagService::getTagValueByName("previously_applied_warning_message")
                . "\n\n" . TagService::getTagValueByName("telegram_question_to_repeater");
            $this->sendEchoMessage($userMessage, self::KEYBOARD_FOR_REPEATER_REQUEST);
        } else {
            $this->sendMessageToAdminChat($this->prepareNotesMessage(
                $confirmedUser,
                $userRequest, // TODO: !!!
                $userRequestsCount
            ));
            $userMessage = TagService::getTagValueByName("thanks_for_registration_message")
                . "\n\n" . TagService::getTagValueByName("telegram_success_answer_to_new_user",);
            $this->sendEchoMessage($userMessage);
        }
    }

    public function prepareNotesMessage(User $user, UserRequest $userRequest, int $requestsCount) {
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
