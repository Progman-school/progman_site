<?php

namespace App\Services;
use App\Exceptions\UserAlert;
use App\Helpers\AppHelper;
use App\Models\Request as UserRequest;
use App\Models\User;
use App\sdks\TelegramBotApiSdk;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class TelegramService extends TelegramBotApiSdk
{
    const REQUEST_STATUS_KEYBOARDS = [
        UserRequest::RECEIVED_STATUS => ['⚠️ new received' => UserRequest::PROCESSED_STATUS],
        UserRequest::CONFIRMED_STATUS => ['⚠️ new confirmed ✏️' => UserRequest::PROCESSED_STATUS],
        UserRequest::PROCESSED_STATUS => ['✅ PROCESSED ✅' => UserRequest::RECEIVED_STATUS],
    ];

    const KEYBOARD_FOR_REPEATER_REQUEST = ['Resend the request' => 'repeat_request'];

    /**
     * @throws UserAlert
     */
    public function manageEntryCommand(): bool
    {
        try {
            $message = $this->request->get("message");
            $command_param = trim(substr($message["text"], $message["entities"][0]["length"]));

            switch (substr($message["text"], 0, $message["entities"][0]["length"])) {  // check command name
                case "/" . UserRequestService::CONFIRM_URL_PARAM:
                    if (!$command_param){
                        $this->sendEchoMessage("Unexpected command command");
                        return true;
                    }
                    $this->confirmRequest($this->request, $command_param);
                    break;
                default:
                    return false;
            }
        } catch (UserAlert $e) {
            $this->sendEchoMessage($e->getMessage());
        } catch (Throwable $e) {
            $this->sendEchoMessage(
                "Sorry! Unexpected command issue.\n Please, connect to manager @"
                . TagService::getTagValueByName("telegram_manager_account")[TagService::getCurrentLanguage()]);
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
        $callbackQuery = $this->request->get("callback_query");
        switch ($callbackQuery["data"]) {
            case UserRequest::RECEIVED_STATUS:
            case UserRequest::PROCESSED_STATUS:
                $this->setRequestStatus(
                    $callbackQuery["message"]["message_id"],
                    (str_contains($callbackQuery["message"]["text"], "Status: " . UserRequest::CONFIRMED_STATUS) &&  $callbackQuery["data"] !== UserRequest::PROCESSED_STATUS) ?  UserRequest::CONFIRMED_STATUS : $callbackQuery["data"]
                );
                break;

                case SupportMessengerService::NEW_SUPPORT_REQUEST_STATUS:
                case SupportMessengerService::PROCESSED_SUPPORT_REQUEST_STATUS:
                    $this->editKeyboardOfMessage(
                        $callbackQuery["message"]["chat"]["id"],
                        $callbackQuery["message"]["message_id"],
                        SupportMessengerService::SUPPORT_MESSAGE_STATUS_KEYBOARDS[
                            SupportMessengerService::switchSupportRequestStatus($callbackQuery["data"])
                        ]
                    );
                    break;
            default:
               return false;
        }
        return true;
    }

    public function setRequestStatus(string $adminMessageId, string $status): void {
        $userRequest = UserRequest::where(
            ["admin_message_id" => $adminMessageId ]
        )->first();
        $userRequest->status = $status;
        $userRequest->save();
        $this->editKeyboardOfMessage(
            $this->adminChatId,
            $adminMessageId,
            self::REQUEST_STATUS_KEYBOARDS[$status]
        );
    }

    /**
     * @throws UserAlert
     * @throws Exception
     */
    public function confirmRequest(Request $request, string $confirmationHash): void
    {
        $userRequest = UserRequestService::confirmUserRequest($confirmationHash);
        $confirmedUser = UserService::addOrGetUserByRequest(
            $userRequest,
            $request["message"]["from"]["first_name"] ?? null,
            $request["message"]["from"]["last_name"] ?? null
        );

        $serviceLogin = $request["message"]["from"]["username"] ?? null;
        if (!$serviceLogin) {
            throw new UserAlert(
                TagService::getTagValueByCurrentLanguage("undefined_telegram_username_user_error")
            );
        }
        UidService::createUid(
            $userRequest->type,
            $request["message"]["from"]['id'],
            $serviceLogin,
            $confirmedUser->id,
            json_encode($request->toArray(), JSON_UNESCAPED_UNICODE)
        );

        $userMessage = "Super!\n". UserRequestService::generateAlertText($userRequest);
//        if ($userRequestsCount > 1) {
//            $userMessage .=
//                "\n\n" . TagService::getTagValueByName("previously_applied_warning_message")[TagService::getCurrentLanguage()]
//                . "\n\n" . TagService::getTagValueByName("telegram_question_to_repeater")[TagService::getCurrentLanguage()];
//            $this->sendEchoMessage(
//                AppHelper::removeHtmlTagsFromString($userMessage),
//                self::KEYBOARD_FOR_REPEATER_REQUEST
//            );
//        } else {
        $applicationDataArray = json_decode($userRequest->application_data, true);
        $confirmedData = [];
        $confirmedData["Telegram"] = "@" . $serviceLogin;
        $confirmedData["First name"] = $request["message"]["from"]["first_name"] ?? "-";
        $confirmedUser["Last name"] = $request["message"]["from"]["last_name"] ?? "-";

        $result = $this->editMessageInAdminChat(
            $userRequest->admin_message_id,
            UserRequestService::createRequestMessageForAdminChat(
                $userRequest,
                array_diff_key($applicationDataArray, array_flip(["uid_type", "name", "contact"])),
                $confirmedData,
                $userRequest->getRepeatsCount(),
                $userRequest->getRepeatsCount()
            ),
            TelegramService::REQUEST_STATUS_KEYBOARDS[$userRequest->status]
        );

        $userMessage .= "\n\n" . TagService::getTagValueByName(
            "thanks_for_registration_message",
            0 ,
                ["new_id" => [
                    TagService::DEFAULT_LANGUAGE => $confirmedUser->id]
                ]
            )[TagService::getCurrentLanguage()]
            . "\n\n" . TagService::getTagValueByName(
                "telegram_success_answer_to_new_user"
            )[TagService::getCurrentLanguage()];
        $this->sendEchoMessage(AppHelper::removeHtmlTagsFromString($userMessage));
    }
}
