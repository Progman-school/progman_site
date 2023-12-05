<?php

use App\Http\Controllers\TelegramApiController;
use Illuminate\Support\Facades\Route;
use App\Services\TelegramApiService;

Route::get("/", [TelegramApiController::class, 'entry']);
Route::get(TelegramApiService::API_ENTRYPOINT, [TelegramApiController::class, 'setHook']);
