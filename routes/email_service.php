<?php

use App\Http\Controllers\MailController;
use App\sdks\EmailServiceSdk;
use App\Services\EmailRequestService;
use Illuminate\Support\Facades\Route;

Route::post(EmailServiceSdk::API_ENTRYPOINT, [MailController::class, 'confirmRequest']);
Route::get("test", [MailController::class, 'sendTestMessage']);
