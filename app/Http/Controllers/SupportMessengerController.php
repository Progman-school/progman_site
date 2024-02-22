<?php

namespace App\Http\Controllers;

use App\Exceptions\UserAlert;
use App\Helpers\ApiHelper;
use App\Services\SupportMessengerService;
use Illuminate\Http\Request;

class SupportMessengerController
{
    /**
     * @throws UserAlert
     */
    public function supportRequestMessage(Request $request): string
    {
        $email = $request->get("email") ?? abort(404);
        $message = $request->get("message") ?? abort(404);
        $currentUrl = $request->get("current_url") ?? abort(404);
        return ApiHelper::createFrontAnswer(
            SupportMessengerService::sendSupportRequestMessageToAdminChat($email, $message, $currentUrl)
        );
    }
}
