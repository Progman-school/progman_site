<?php

use App\Http\Controllers\TelegramApiController;
use App\sdks\TelegramBotApiSdk;
use Illuminate\Support\Facades\Route;

Route::post(TelegramBotApiSdk::API_ENTRYPOINT, [TelegramApiController::class, 'entry']);
//Route::get("/", [TelegramApiController::class, 'setHook']);
