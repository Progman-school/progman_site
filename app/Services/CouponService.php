<?php

namespace App\Services;

use App\Exceptions\UserAlert;
use App\Models\Coupon;
use App\Models\Coupon\CouponType;
use Exception;

class CouponService
{
    const COUPON_NUMBER_KEY = 'serial_number';

    public static function checkCouponBy(string $serialNumber, mixed $paramValue = null, string $paramName = 'id'): Coupon
    {
        $serialNumber = strtoupper(trim($serialNumber));
        if ($paramValue === null) {
            $coupon = Coupon::where(self::COUPON_NUMBER_KEY, $serialNumber)
                ->where('is_active', true)
                ->with('couponUnit', 'couponType')
                ->first();
            if ($coupon === null) {
                throw new UserAlert("The coupon (serial_number:{$paramValue}) is not found!");
            }
        } else {
            $couponType = CouponType::where($paramName, $paramValue)->first();
            if ($couponType) {
                $coupon = Coupon::where(self::COUPON_NUMBER_KEY, $serialNumber)
                    ->where('is_active', true)
                    ->where('coupon_type_id', $couponType->id)
                    ->with('couponUnit', 'couponType')
                    ->first();
            } else {
                $coupon = Coupon::where(self::COUPON_NUMBER_KEY, $serialNumber)
                    ->where('is_active', true)
                    ->where($paramName, $paramValue)
                    ->with('couponUnit', 'couponType')
                    ->first();
            }
            if ($coupon === null) {
                throw new UserAlert("The coupon ({$paramName}:{$paramValue}) is not found!");
            }
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
        return $coupon;
    }

    public static function generateCouponSerialNumber(int $couponId, ?string $prefix = null): string {
        $charFirst = chr(rand(ord('a'), ord('z')));
        $charLast = chr(rand(ord('a'), ord('z')));
        $serialNumber = ($prefix ? ($prefix . "-") : strtoupper($charFirst)) . $couponId .
            strtoupper($charLast . substr(md5($couponId), 0, $prefix ? rand(4, 5) : rand(6, 8)));

        if (Coupon::where(self::COUPON_NUMBER_KEY, $serialNumber)->exists()) {
            throw new Exception(
                "Error while generating the serial number, the serial number '{$serialNumber}' is already exists!"
            );
        }
        return strtoupper($serialNumber);
    }

    /**
     * @throws UserAlert
     * @throws Exception
     */
    public static function generateCouponBySettingCode(string $code, string $language): Coupon
    {
        $couponParams = [
            'name' => 'Special VIP Offer',
            'language' => $language,
            'method' => Coupon::AUTO_GENERATED_METHOD,
            'max_times' => 1,
            'area_type' => Coupon::ONLINE_AREA_TYPE,
            'area' => $_SERVER['HTTP_REFERER'],
            'placement_id' => 1,
        ];
        $code = substr($code, 1);
        list(
            $couponParams['coupon_type_id'],
            $couponParams['coupon_unit_id'],
            $couponParams['value'],
            $couponParams['days_to_expire']
            ) = array_map(fn($item) => (int) $item, str_split($code, 2));
        $couponParams['expired_at'] = now()->addDays($couponParams['days_to_expire'])->toDateTimeString();
        $coupon = new Coupon($couponParams);
        $coupon->save();
        $coupon->serial_number = self::generateCouponSerialNumber($coupon->id, 'VIP');
        $coupon->save();

        return self::checkCouponBy($coupon->serial_number, $coupon->id);
    }

}
