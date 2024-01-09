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
    public function entry(Request $request): string
    {
        if (!$request->get("action")) {
            abort(404);
        }
        $emailRequestService = new EmailRequestService($request);

        switch ($request->action) {
            case "confirm_request":
                return redirect(
                    EmailRequestService::API_ROUT .  EmailRequestService::API_ENTRYPOINT .
                    "?action=show_confirm_result&text=" . urlencode($emailRequestService->confirmRequest())
                );
            case "show_confirm_result":
                return $emailRequestService->showResultPage(urldecode($request->text) ?? abort(404));
            default:
                abort(404);
        }
    }

}
