<?php

use App\Http\Controllers\CertificateController;
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
Route::get('check_certificate', [CertificateController::class, 'checkCertificate']);

Route::get('current_language', [TagController::class, 'getCurrentLanguage']);
Route::get('switch_tag_language', [TagController::class, 'switchTagLanguage']);
Route::get('all_tags', [TagController::class, 'getAllTags']);
Route::get('tag_value_by_name', [TagController::class, 'getTagValueByName']);

// tmp rebuilders
//Route::get('rebuild_tags', [\App\Http\Controllers\DBRebuilder::class, 'rebuildTags']);
Route::get('rebuildAllFromRequests', [\App\Http\Controllers\DBRebuilder::class, 'rebuildAllFromRequests']);



