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
        }

        /** @var User $checkUser */
        $checkUser = UserRequest::where('hash', $userRequest->hash)->first()->users()->first();

        if ($checkUser?->id) {
            return $checkUser;
        }

        $checkUser = new User();
        $checkUser->first_name = $request["message"]["from"]["first_name"] ?? null;
        $checkUser->last_name = $request["message"]["from"]["last_name"] ?? null;
        $checkUser->save();
        $newId = $checkUser->getKey();

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
        if (!$userRequest?->id) {
            throw new UserAlert(
                TagService::getTagValueByName("invalid_command_from_site_error")[TagService::getCurrentLanguage()]
            );
        }
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
