<?php

use App\sdks\EmailServiceSdk;
use Illuminate\Support\Facades\Route;

Route::post(EmailServiceSdk::API_ENTRYPOINT, [EmailServiceSdk::class, 'entry']);
