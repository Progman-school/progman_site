<?php

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
//Route::post('savetest', [RequestsController::class, 'addNewRequest']);
//
//Route::post('check_certificate', [CertificateController::class, 'checkCertificate']);

Route::get('get_current_language', [TagController::class, 'getCurrentLanguage']);
Route::put('switch_tag_language', [TagController::class, 'switchTagLanguage']);
Route::get('get_all_tag_contents', [TagController::class, 'getAllContent']);
Route::get('get_tag_value_by_name', [TagController::class, 'getTagValueByName']);

// tmp rebuilders
Route::get('rebuild_tags', [\App\Http\Controllers\DBRebuilder::class, 'rebuildTags']);


