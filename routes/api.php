<?php

use App\Http\Controllers\TagController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//Route::get('wh_set', [UserSignController::class, 'setHook']);
//Route::get('wh_get', [UserSignController::class, 'getHookInfo']);
//Route::post('wh_set', [UserSignController::class, 'index']);
//
//Route::post('savetest', [RequestsController::class, 'addNewRequest']);
//
//Route::post('check_certificate', [CertificateController::class, 'checkCertificate']);

Route::post('get_current_language', [TagController::class, 'getCurrentLanguage']);
Route::post('change_language', [TagController::class, 'changeLanguage']);
Route::post('get_content_by_tag', [TagController::class, 'getContentByTag']);
Route::post('get_all_tag_contents', [TagController::class, 'getAllContent']);
