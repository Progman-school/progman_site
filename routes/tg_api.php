<?php

use App\Http\Controllers\TelegramApiController;
use Illuminate\Support\Facades\Route;

Route::get('/', [TelegramApiController::class, 'setHook']);
