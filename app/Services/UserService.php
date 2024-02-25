<?php

namespace App\Services;

use App\Exceptions\UserAlert;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use App\Models\Request as UserRequest;

class UserService
{
    /**
     * @throws Exception
     */
    public static function addOrGetUserByRequest(
        UserRequest $userRequest,
        ?string $firstName = null,
        ?string $lastName = null
    ): User
    {
        /** @var User $checkUser */
        $checkUser = $userRequest->users()->first();

        if ($checkUser?->id) {
            return $checkUser;
        }

        $checkUser = new User();
        $checkUser->first_name = $firstName;
        $checkUser->last_name = $lastName;
        $checkUser->save();
        $checkUser->requests()->attach($userRequest->id);

        return $checkUser;
    }

}
