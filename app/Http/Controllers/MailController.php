<?php

namespace App\Http\Controllers;

use App\Mail\ConfirmApplication;
use Illuminate\Support\Facades\Mail;

class MailController extends MainController
{
    public function sendTestMessage()
    {
        Mail::send(
            new ConfirmApplication("https://google.com", 32, "Some test text")
        );
    }
}
