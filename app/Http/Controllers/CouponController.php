<?php

namespace App\Http\Controllers;

use App\Exceptions\UserAlert;
use App\Helpers\ApiHelper;
use App\Services\CouponService;
use Illuminate\Http\Request;
use App\Services\EmailService;

class CouponController extends MainController
{
    public function checkCoupon(string $serialNumber): string
    {
        return ApiHelper::createFrontAnswer(
            CouponService::checkCoupon($serialNumber)
        );
    }
}
