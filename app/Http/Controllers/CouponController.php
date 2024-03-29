<?php

namespace App\Http\Controllers;

use App\Exceptions\UserAlert;
use App\Helpers\ApiHelper;
use App\Services\CouponService;
use Illuminate\Http\Request;
use App\Services\EmailService;

class CouponController extends MainController
{
    /**
     * @throws UserAlert
     */
    public function checkCoupon(string $serialNumber): string
    {
        return ApiHelper::createFrontAnswer(
            CouponService::checkCouponBy($serialNumber)->toArray()
        );
    }

    /**
     * @throws UserAlert
     */
    public function checkCouponType(int $typeId, string $serialNumber): string
    {
        return ApiHelper::createFrontAnswer(
            CouponService::checkCouponBy($serialNumber, $typeId)->toArray()
        );
    }
}
