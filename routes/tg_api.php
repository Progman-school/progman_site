<?php

use App\Http\Controllers\TelegramApiController;
use App\sdks\TelegramBotApiSdk;
use Illuminate\Support\Facades\Route;

Route::get("/", [TelegramApiController::class, 'entry']);
Route::get(TelegramBotApiSdk::API_ENTRYPOINT, [TelegramApiController::class, 'setHook']);
