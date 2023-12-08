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
    public static function confirmUserRequest(Request $request, string $hash): User
    {
        $userRequest = UserRequest::where("hash", $hash)->first();
        if (!$userRequest->get("id")) {
            throw new UserAlert(
                TagService::getTagValueByName("invalid_command_from_site_error")
            );
        }
        return self::addOrGetUserByRequest($request, $userRequest);
    }

    /**
     * @throws UserAlert
     */
    public static function getCountOfUserRequests(User $user, $oftenRegistrationWarning = false): int{
        $requests = $user->requests();
        $delayDate = time() - (self::REQUEST_DELAY_DAYS * 3600 * 24);
        if ($oftenRegistrationWarning & strtotime($requests->latest()->first()->created_at) > $delayDate) {
            throw new UserAlert(
                TagService::getTagValueByName("too_often_registration_user_error")
            );
        }
        return $requests->count();
    }
}
