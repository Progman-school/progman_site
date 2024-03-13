<?php

use App\Http\Controllers\CertificateController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\PreloadedContentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\SupportMessengerController;
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
Route::post('add_request', [RequestController::class, 'addRequest']);
Route::post('check_certificate', [CertificateController::class, 'checkCertificate']);

Route::get('current_language', [TagController::class, 'getCurrentLanguage']);
Route::get('all_tags', [TagController::class, 'getAllTags']);
Route::get('tag_value_by_name', [TagController::class, 'getTagValueByName']);
Route::get('language_locate_meta_tags', [TagController::class, 'getLanguageLocateMetaTagsContents']);
Route::patch('switch_tag_language', [TagController::class, 'switchTagLanguage']);
Route::patch('change_language_to/{language}', [TagController::class, 'changeTagLanguageTo']);

Route::get('get_all_courses', [PreloadedContentController::class, 'getAllCourses']);

Route::post("support_request_message", [SupportMessengerController::class, 'supportRequestMessage']);

Route::get('check_coupon/any/{serialNumber}', [CouponController::class, 'checkCoupon']);
Route::get('check_coupon/{typeId}/{serialNumber}', [CouponController::class, 'checkCouponType']);

Route::get('get_product_by/name/{name}', [ProductController::class, 'getProductsByName']);

Route::post('process_test', [RequestController::class, 'addRequest']);
