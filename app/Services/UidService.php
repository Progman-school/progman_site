<?php

namespace App\Services;

use App\Models\Uids\Uid;
use App\Models\Uids\UidInterface;
use Exception;

class UidService
{
    const AVAILABLE_TYPES = [
        "telegram",
        "email",
    ];

    public static function createUid(
        string $uidType,
        string $serviceUid,
        string $serviceLogin,
        int $userId,
        string $data,
        ): UidInterface
    {

        if (!in_array($uidType, self::AVAILABLE_TYPES)) {
            throw new Exception("Unexpected uid type '{$uidType}'");
        }
        $uid = ("App\Models\Uids\\" . ucfirst($uidType))::where("service_uid", $serviceUid)->first();

        if (!$uid?->id) {
            $uid = new ("App\Models\Uids\\" . ucfirst($uidType))();
            $uid->service_uid = $serviceUid;
            $uid->user_id = $userId;
        }
        $uid->service_login = $serviceLogin;
        $uid->data = $data;
        $uid->save();
        if (!$uid?->id) {
            throw new Exception("Error while creating/updating uid '{$serviceUid}'({$uidType})");
        }
        return $uid;
    }

}
