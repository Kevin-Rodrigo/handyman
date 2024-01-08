<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\UserManagement\Http\Controllers\Api\V1\OTPVerificationController;
use Modules\UserManagement\Http\Controllers\Api\V1\PasswordResetController;
use Modules\UserManagement\Http\Controllers\Api\V1\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//admin
Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Api\V1\Admin', 'middleware' => ['auth:api']], function () {
    Route::group(['prefix' => 'user', 'as' => 'user.',], function () {
        Route::get('list', 'UserController@index');
    });
});


//User
Route::group(['prefix' => 'user', 'namespace' => 'Api\V1'], function () {
    //verification
    Route::group(['prefix' => 'verification'], function () {
        Route::post('send-otp', [OTPVerificationController::class, 'check']);
        Route::post('verify-otp', [OTPVerificationController::class, 'verify']);
    });

    //forget password
    Route::group(['prefix' => 'forget-password'], function () {
        Route::post('send-otp', [PasswordResetController::class, 'check']);
        Route::post('verify-otp', [PasswordResetController::class, 'verify']);
        Route::put('reset', [PasswordResetController::class, 'reset_password']);
    });
});

