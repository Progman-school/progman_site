<?php

namespace App\Services;

use App\Exceptions\UserAlert;
use App\Models\TestResult;
use App\Models\Uids\Email;
use App\Mail\TestResult as TestResultMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class TestService
{

    /**
     * @throws UserAlert
     * @throws \Exception
     */
    public static function processTest(array $testResultData): string
    {
        $resultLetterText = $testResultData["result_message"];
        unset($testResultData["result_message"]);

        if ($testResultData['uid_type'] === UidService::TELEGRAM_UID_TYPE) {
            throw new UserAlert("Telegram uid type is not supported yet for tests");
        }
        $uid = Email::where('service_uid', $testResultData['contact'])->first();

        if ($uid) {
            $user = $uid->user;
        } else {
            $user = new User();
            $userFullName = explode(' ', $testResultData['name']);
            $user->first_name = $userFullName[0] ?? null;
            $user->last_name = $userFullName[1] ?? null;
            $user->save();
            $uid = UidService::createUid(
                UidService::EMAIL_UID_TYPE,
                $testResultData['contact'],
                null,
                $user->id,
                json_encode($testResultData, JSON_UNESCAPED_UNICODE);
            );
        }

        $coupon = CouponService::generateCouponBySettingCode($testResultData['c']);

        $testResult = new TestResult($testResultData);
        $testResult->user()->associate($user);
        $testResult->coupon()->associate($coupon);
        $testResult->save();

        Mail::to($testResultData['contact'])->send(new TestResultMail(
            $resultLetterText,
            $coupon
        ));

        return "Test result is saved successfully!";
    }

}
