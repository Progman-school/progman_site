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
            $serviceUid = $request["message"]["from"]["id"];
            $serviceLogin = $request["message"]["from"]["username"] ?? null;
            if (!$serviceLogin) {
                throw new UserAlert(
                    TagService::getTagValueByCurrentLanguage("undefined_telegram_username_user_error")
                );
            }
        } elseif ($userRequest->type == "email") {
            $serviceUid = $userRequest->uid;
            $serviceLogin = $userRequest->uid;
        } else {
            throw new Exception("Unexpected user request type '{$userRequest->type}'");
        }

        /** @var User $checkUser */
        $checkUser = $userRequest->users()->first();

        if ($checkUser?->id) {
            return $checkUser;
        }

        $checkUser = new User();
        $checkUser->first_name = $request["message"]["from"]["first_name"] ?? null;
        $checkUser->last_name = $request["message"]["from"]["last_name"] ?? null;
        $checkUser->save();
        $newId = $checkUser->getKey();
        $checkUser->requests()->attach($userRequest->id);

        UidService::createUid(
            $userRequest->type,
            $serviceUid,
            $serviceLogin,
            $newId,
            json_encode($request->toArray(), JSON_UNESCAPED_UNICODE)
        );

        return $checkUser;
    }

    /**
     * @throws UserAlert
     * @throws Exception
     */
    public static function confirmUserRequest(string $hashString): UserRequest
    {
        $hashData = explode("-", $hashString);
        $userRequest = UserRequest::where(["type" => $hashData[0], "hash" => $hashData[1]])->first();
        if ($userRequest->admin_message_id) {
            throw new UserAlert(
                TagService::getTagValueByName("warning_request_is_already_confirmed")[TagService::getCurrentLanguage()]
            );
        }
        if (!$userRequest?->id) {
            throw new UserAlert(
                TagService::getTagValueByName("invalid_command_from_site_error")[TagService::getCurrentLanguage()]
            );
        }
        $userRequest->status = UserRequest::RECEIVED_STATUS;
        return $userRequest;
    }

    /**
     * @throws UserAlert
     */
    public static function getCountOfUserRequests(User $user, $oftenRegistrationWarning = false): int{
        $requests = $user->requests();
        $delayDate = time() - (self::REQUEST_DELAY_DAYS * 3600 * 24);
        if ($oftenRegistrationWarning & strtotime($requests->latest()->first()?->created_at) > $delayDate) {
            throw new UserAlert(
                TagService::getTagValueByName("too_often_registration_user_error")
            );
        }
        return $requests->count();
    }
}
