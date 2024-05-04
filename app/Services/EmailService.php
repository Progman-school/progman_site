<?php

namespace App\Services;

use App\Exceptions\UserAlert;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\Request as UserRequest;
use App\sdks\EmailServiceSdk;
use App\sdks\TelegramBotApiSdk;
use Exception;
use Illuminate\Http\Request;

class EmailService extends EmailServiceSdk
{
    protected TelegramService $telegramService;

    public function __construct(Request $request) {
        parent::__construct($request);
        $this->telegramService = new TelegramService($request);
    }

    /**
     * @throws UserAlert
     * @throws Exception
     */
    public function confirmRequest(): string
    {
        $confirmationHash = $this->request->{UserRequestService::CONFIRM_URL_PARAM};

        $userRequest = UserRequestService::confirmUserRequest($confirmationHash);
        $userName = explode(" ", $userRequest->name);
        $confirmedUser = UserService::addOrGetUserByRequest(
            $userRequest,
            $userName[0] ?? null,
            $userName[1] ?? null
        );

        UidService::createUid(
            $userRequest->uid_type,
            $userRequest->contact,
            $userRequest->contact,
            $confirmedUser->id,
            json_encode($this->request->toArray(), JSON_UNESCAPED_UNICODE)
        );

        $applicationDataArray = json_decode($userRequest->application_data, true);
        $confirmedData = [];
        $confirmedData["Email"] = $userRequest->contact;
        $confirmedData["First name"] = $userName[0] ?? "-";
        $confirmedUser["Last name"] = $userName[1] ?? "-";

        $product = Product::where("id", $userRequest->product_id)->first();

        $coupon = $userRequest->coupon()->first() ?? null;
        $couponUnit = $coupon?->couponUnit()->first();
        $result = $this->telegramService->editMessageInAdminChat(
            $userRequest->admin_message_id,
            UserRequestService::createRequestMessageForAdminChat(
                $userRequest,
                array_diff_key(
                        $applicationDataArray,
                        array_flip(["uid_type", "name", "contact"])
                    ) + [
                    'product_name' => $product->getName(),
                    'product_unit_price' => $product->unit_price,
                    'coupon_value' => $coupon ? $coupon->value . $couponUnit->symbol : 'none',
                    'total' => CouponService::countPriceByCoupon($product->unit_price, $userRequest->quantity, $coupon),
                    ],
                $confirmedData,
                $userRequest->getRepeatsCount(),
                $userRequest->getRepeatsCount()
            ),
            TelegramService::REQUEST_STATUS_KEYBOARDS[$userRequest->status]
        );

        return TagService::getTagValueByName(
            "successfully_confirmed_request_alert_text",
            0,
            ["new_request_id" => [TagService::DEFAULT_LANGUAGE => $userRequest->id]
            ]
        )[TagService::getCurrentLanguage()];
    }

    public function showResultPage(string $text): string {
        return view(
            'api_answers.result_page_of_email_action',
            ['text' => str_replace("\n", "<br/>", $text)]
        )->render();
    }
}
