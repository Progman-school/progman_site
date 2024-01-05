<?php

use App\Http\Controllers\MailController;
use App\sdks\EmailServiceSdk;
use Illuminate\Support\Facades\Route;

Route::post(EmailServiceSdk::API_ENTRYPOINT, [EmailServiceSdk::class, 'entry']);
Route::get("test", [MailController::class, 'sendTestMessage']);
