<?php

namespace App\Services;

use App\Exceptions\UserAlert;
use App\Mail\ConfirmApplication;
use App\Models\Product;
use App\sdks\EmailServiceSdk;
use Exception;
use Illuminate\Http\Request;
use App\Models\Request as UserRequest;
use Illuminate\Support\Facades\Mail;

class UserRequestService
{
    const CONFIRM_URL_PARAM = "start";
    const REQUEST_DELAY_DAYS = 3;
    const MAX_REQUEST_REPEATS = 3;

    /**
     * @throws UserAlert
     */
    public static function addRequest(Request $request): ?array {
        if (!in_array($request->uid_type, UidService::AVAILABLE_TYPES)) {
            throw new Exception("Unexpected uid type '{$request->uid_type}'");
        }
        $requestData = $request->all();
        $product = Product::where('id', $requestData['product_id'])->first();
        if (!$product) {
            throw new Exception("Product with (id:{$requestData['product_id']}) not found");
        }

        $coupon = null;
        if(isset($requestData['coupon']) && $requestData['coupon']) {
            $coupon = CouponService::checkCouponBy($requestData['coupon']);
        }


        $userRequest = new UserRequest($requestData);
        $userRequest->current_product_price = $product->unit_price;
        $userRequest->application_data = json_encode($requestData, JSON_UNESCAPED_UNICODE);
        $userRequest->hash = self::getRequestHash($request);
        $userRequest->coupon()->associate($coupon);
        $userRequest->language = TagService::getCurrentLanguage();
        $userRequest->status = UserRequest::RECEIVED_STATUS;
        $userRequest->save();

        $product = $userRequest->product()->first();

        $return = [
            'hash_link' => null,
            'alert_text' => TagService::getTagValueByCurrentLanguage(
                "instructions_of_request_confirmation_by_{$userRequest->uid_type}"
            )
        ];

        if ($userRequest->uid_type == "telegram") {
                $return['href_link'] = "tg://resolve?domain=" . config("services.telegram.bot_login") .
                    "&" . self::CONFIRM_URL_PARAM . "={$userRequest->uid_type}-{$userRequest->hash}";
            $return['alert_button_name'] = 'Open Telegram';
        } elseif ($userRequest->uid_type == "email") {
            Mail::to($request->contact)->send(new ConfirmApplication(
                "https://{$_SERVER['HTTP_HOST']}/" . EmailServiceSdk::API_ROUT . EmailServiceSdk::API_ENTRYPOINT .
                "?" . http_build_query([
                    "action" => "confirm_request",
                    self::CONFIRM_URL_PARAM => "{$userRequest->uid_type}-{$userRequest->hash}",
                ]),
                TagService::getTagValueByCurrentLanguage(
                    'email_confirmation_message_text',
                    0 ,
                    [
                        'product_name' => [TagService::DEFAULT_LANGUAGE => $product->getName()],
                    ]
                )
            ));
        }
        $couponUnit = $coupon?->couponUnit()->first();
        $telegramService = new TelegramService();
        $result = $telegramService->sendMessageToAdminChat(
            UserRequestService::createRequestMessageForAdminChat(
                $userRequest,
                array_diff_key($request->toArray(), array_flip(["uid_type", "name", "contact"])
                ) + [
                    'product_name' => $product->getName(),
                    'product_unit_price' => $product->unit_price,
                    'coupon_value' => $coupon ? $coupon->value . $couponUnit->symbol : 'none',
                    'total' => CouponService::countPriceByCoupon($product->unit_price, $userRequest->quantity, $coupon),
                ]
            ),
            TelegramService::REQUEST_STATUS_KEYBOARDS[$userRequest->status]
        );

        $userRequest->admin_message_id = $result["result"]["message_id"];
        $userRequest->save();

        if ($coupon) {
            $coupon->used_times++;
            $coupon->save();
        }

        return $return;
    }

    protected static function getRequestHash(Request $request): string {
        return md5(
            UserRequest::all()->count() - 1 . '-' . print_r($request->toArray(), 1). '=' . microtime()
        );
    }

    public static function createRequestMessageForAdminChat(
        UserRequest $userRequest,
        array $productData,
        array $confirmedData = null,
        int $requestsCount = null,
        string $firstRequest = null
    ): string
    {
        $message = "Request! #{$userRequest->id} ({$userRequest->uid_type} type)\n";
        $message .= "Lang: {$userRequest->language}\n";
        $user = $userRequest->user()->first();
        if ($user) {
            $message .= "User id: {$user->id}\n";
        }
        $message .= "Name: {$userRequest->name}\n";
        $message .= "Contact: {$userRequest->contact}\n\n";

        $message .= "PRODUCT:\n";
        $message .= "Product name: {$productData['product_name']}\n";
        foreach ($productData as $keyProductItem => $productItem) {
            $message .= "$keyProductItem: $productItem\n";
        }
        $message .= "\n";

        $message .= "Created: " . date("m/d/Y H:i:s", strtotime($userRequest->created_at)) . "\n";
        $message .= "Updated: " . ($userRequest->updated_at ? date("m/d/Y H:i:s", strtotime($userRequest->updated_at)) : "-") . "\n";
        $message .= "Status: {$userRequest->status}\n\n";

        if ($confirmedData) {
            $message .= "CONFIRMED UID DATA:\n";
            foreach ($confirmedData as $keyContactItem => $contactItem) {
                $message .= "$keyContactItem: $contactItem\n";
            }
            $message .= "\n";
        }

        if ($requestsCount && $requestsCount > 1) {
            $message .= "REPEATER: {$requestsCount}\n\n";
            $message .= "first request: " . ($firstRequest ? date("d.m.Y", strtotime($firstRequest)) : "none") . "\n";
        }

//        $message .= "Application: \n";
//        foreach (json_decode($userRequest->application_data, true) as $keyTestItem => $testItem) {
//            $message .= "$keyTestItem: $testItem\n";
//        }
        return $message;
    }

    /**
     * @throws UserAlert
     * @throws Exception
     */
    public static function confirmUserRequest(string $hashString): UserRequest
    {
        $hashData = explode("-", $hashString);
        $userRequest = UserRequest::where(["uid_type" => $hashData[0], "hash" => $hashData[1]])->first();
        if (!$userRequest?->id) {
            throw new UserAlert(
                TagService::getTagValueByName("invalid_command_from_site_error")[TagService::getCurrentLanguage()]
            );
        }

        TagService::setCurrentLanguage($userRequest->language);
        if ($userRequest->status == UserRequest::CONFIRMED_STATUS) {
            throw new UserAlert(
                TagService::getTagValueByName("warning_request_is_already_confirmed")[TagService::getCurrentLanguage()]
            );
        }

        if ($userRequest->status == UserRequest::PROCESSED_STATUS) {
            throw new UserAlert(
                TagService::getTagValueByName("warning_request_is_already_confirmed")[TagService::getCurrentLanguage()]
            );
        }

        $userRequest->status = UserRequest::CONFIRMED_STATUS;
        $userRequest->save();
        return $userRequest;
    }
}
