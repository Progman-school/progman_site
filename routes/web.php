<?php

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

Route::get('/{lang?}/{page?}', function ($lang, $page = null) {
//    if ($lang) {
//        TagService::setCurrentLanguage($lang);
//    }
    return "check"; //view('index');
});
