<?php

namespace App\Http\Controllers;

use App\Mail\ConfirmApplication;
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
}
