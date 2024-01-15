<?php

use App\Http\Controllers\CertificateController;
use App\Http\Controllers\PreloadedContentController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\TagController;
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
Route::post('add_request', [RequestController::class, 'addRequest']);
//
Route::get('check_certificate', [CertificateController::class, 'checkCertificate']);
//Route::get('check_session', function (\Illuminate\Http\Request $request) {
//    return print_r($request->session()->all());
//});

Route::get('current_language', [TagController::class, 'getCurrentLanguage']);
Route::get('all_tags', [TagController::class, 'getAllTags']);
Route::get('tag_value_by_name', [TagController::class, 'getTagValueByName']);
Route::patch('switch_tag_language', [TagController::class, 'switchTagLanguage']);

Route::get('get_courses_list', [PreloadedContentController::class, 'getCoursesList']);



