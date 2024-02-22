<?php

use App\Http\Controllers\MailController;
use App\sdks\EmailServiceSdk;
use Illuminate\Support\Facades\Route;

Route::get(EmailServiceSdk::API_ENTRYPOINT, [MailController::class, 'entry']);
