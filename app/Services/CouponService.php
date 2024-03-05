<?php

namespace App\Services;

use App\Exceptions\UserAlert;
use App\Models\Coupon;
use App\Models\Coupon\CouponType;

class CouponService
{
    const COUPON_NUMBER_KEY = 'serial_number';

    public static function checkCoupon(string $serialNumber, string $typeName = null): array
    {
        if ($typeName === null) {
            $coupon = Coupon::where(self::COUPON_NUMBER_KEY, $serialNumber)
                ->where('is_active', true)
                ->with('type', 'unit')
                ->first();
        } else {
            $couponType = CouponType::where('name', $typeName)->first();
            if ($couponType === null) {
                throw new UserAlert("The coupon type {$typeName} is not found!");
            }
            $coupon = Coupon::where(self::COUPON_NUMBER_KEY, $serialNumber)
                ->where('is_active', true)
                ->where('coupon_type_id', $couponType->id)
                ->with('type', 'unit')
                ->first();
        }

        if ($coupon === null) {
            throw new UserAlert('The coupon is not found!');
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
