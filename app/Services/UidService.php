<?php

namespace App\Services;

use App\Models\Uids\Uid;
use App\Models\Uids\UidInterface;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;

class UidService
{
    const AVAILABLE_TYPES = [
        "telegram",
        "email",
    ];

    public static function createUidByRequest(Request $request, User $user, string $uidType): UidInterface
    {
        if (!in_array($uidType, self::AVAILABLE_TYPES)) {
            throw new Exception("Unexpected uid type '{$uidType}'");
        }
        /** @var Uid $uid */
        $uid = new (ucfirst($uidType))();
        $uid->data = $request->json();
        if ($uidType == "telegram") {
            $uid->service_login = $request->message->from->username;
            $uid->service_uid = $request->message->from->id;
            $uid->user_id = $user->id;
        }
        $uid->save();
        return $uid;
    }
}
