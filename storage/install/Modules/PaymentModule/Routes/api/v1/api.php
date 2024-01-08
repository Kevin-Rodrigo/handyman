<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\PaymentModule\Http\Controllers\Api\V1\Customer\BonusController;
use Modules\PaymentModule\Http\Controllers\Api\V1\Customer\OfflinePaymentController;

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

Route::group(['prefix' => 'admin', 'as'=>'admin.', 'namespace' => 'Api\V1\Admin','middleware'=>['auth:api']], function () {
    Route::group(['prefix'=>'payment-config'],function (){
        Route::get('get', 'PaymentConfigController@payment_config_get');
        Route::put('set', 'PaymentConfigController@payment_config_set');
    });
});

Route::get('customer/offline-payment/methods', [OfflinePaymentController::class, 'get_methods']);

Route::group(['prefix' => 'customer', 'as'=>'customer.', 'middleware'=>['auth:api']], function () {
    Route::get('bonus-list', [BonusController::class, 'get_bonuses']);
});
