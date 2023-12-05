<?php

use App\Http\Controllers\TelegramApiController;
use Illuminate\Support\Facades\Route;

Route::get('set_hook', [TelegramApiController::class, 'setHook']);
Route::get('/', function () {return "ok";});
