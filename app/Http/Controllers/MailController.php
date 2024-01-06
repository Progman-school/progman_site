<?php

namespace App\Http\Controllers;

use App\Exceptions\UserAlert;
use App\Mail\ConfirmApplication;
use Illuminate\Http\Request;
use App\Services\EmailRequestService;
use Illuminate\Support\Facades\Mail;

class MailController extends MainController
{
    public function sendTestMessage(): string
    {
        return Mail::to(
            "denisb.intouch@gmail.com"
        )->send(
            new ConfirmApplication("https://google.com", 32, "Some test text")
        )->toString();
    }

    /**
     * @throws UserAlert
     */
    public function confirmRequest(Request $request): string
    {
        $emailRequestService = new EmailRequestService($request);
        return $emailRequestService->confirmRequest();
    }

}
