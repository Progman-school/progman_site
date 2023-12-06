<?php

namespace App\Services;

use App\Exceptions\UserAlert;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use App\Models\Request as UserRequest;

class UserService
{
    const REQUEST_DELAY_DAYS = 3;

    /**
     * @throws Exception
     */
    public static function addOrGetUserByRequest(Request $request, UserRequest $userRequest): User
    {
        if ($userRequest->type == "telegram") {
            $serviceUid = $request->message->from->id;
            $serviceLogin = $request->message->from->get("username");
        }

        /** @var User $checkUser */
        $checkUser = User::with('telegrams')->with("emails")
            ->where('service_uid', $serviceUid)
            ->orWhere('hash', $userRequest->hash)
            ->first();

        if (!$checkUser->get("id")) {
            $checkUser = new User();
        }

        $checkUser->first_name = $request->message->from->first_name;
        $checkUser->last_name = $request->message->from->last_name;
        $checkUser->save();
        $newId = $checkUser->getKey();

        UidService::createUid(
            $userRequest->type,
            $serviceUid,
            $serviceLogin,
            $newId,
            $request->json()
        );

        return $checkUser;
    }

    /**
     * @throws UserAlert
     * @throws Exception
     */
    public static function registerUser(Request $request, string $hash): void
    {
        $userRequest = UserRequest::where("hash", $hash)->first();
        if (!$userRequest->get("id")) {
            throw new UserAlert(
                TagService::getTagValueByName("invalid_command_from_site_error")
            );
        }

        $user = self::addOrGetUserByRequest($request, $userRequest);
        $requests = $user->requests();
        $requests->latest()->first();
        $delayDate = time() - (self::REQUEST_DELAY_DAYS * 3600 * 24);
        if (strtotime($requests->latest()->first()->created_at) > $delayDate) {
            throw new UserAlert(
                TagService::getTagValueByName("too_often_registration_user_error")
            );
        }

        // TODO: rewrite this ...
        if (@$userInfo["repeater"]) {
            $userMessage = TagService::getTagValueByName("previously_applied_warning_message")
                . "\n\n" . TagService::getTagValueByName("telegram_question_to_repeater");
            $bot->echoMessage($userMessage, self::KEY_FOR_REPEATER_REQUEST);
        } else {
            $this->sendAdminNotes($bot, $userInfo['id']);
            $userMessage = TagService::getTagValueByName("thanks_for_registration_message")
                . "\n\n" . TagService::getTagValueByName("telegram_success_answer_to_new_user",);
            $bot->echoMessage($userMessage);
        }

    }
}
