<?php

namespace App\Services;

use App\Exceptions\UserAlert;
use App\Models\Coupon;

class CouponService
{
    public static function checkCoupon(string $serialNumber, string $typeId = null): array
    {
        $coupon = Coupon::where('serial_number', $serialNumber)
            ->where('coupon_type_id', $typeId)
            ->with('type', 'unit')
            ->first();

        if ($coupon === null) {
            throw new UserAlert('The coupon not found!');
        }
        if ($coupon->expired_at < now()) {
            throw new UserAlert('The coupon is expired!');
        }
        if ($coupon->max_times !== null && $coupon->used_times >= $coupon->max_times) {
            throw new UserAlert('The coupon is used up, and not available anymore!');
        }

        return $coupon->toArray();
    }


}
