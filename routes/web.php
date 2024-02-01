<?php

use App\Http\Controllers\Controller;
use App\Services\TagService;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/{lang?}/{page?}', function ($lang = null, $page = null) {
    return Controller::index($lang);
})
    ->where('lang', implode('|', TagService::LANG_LIST))
    ->where('page', '.*');

