<?php

use Illuminate\Support\Facades\Route;
use Modules\ProviderManagement\Http\Controllers\Api\V1\Customer\ProviderController;
use Modules\ProviderManagement\Http\Controllers\Api\V1\Provider\ConfigController as ProviderConfigController;
use Modules\ProviderManagement\Http\Controllers\Api\V1\Provider\Report\BookingReportController;
use Modules\ProviderManagement\Http\Controllers\Api\V1\Provider\Report\BusinessReportController;
use Modules\ProviderManagement\Http\Controllers\Api\V1\Provider\Report\TransactionReportController;

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

Route::group(['prefix' => 'provider', 'as' => 'provider.', 'namespace' => 'Api\V1\Provider'], function () {
    Route::post('forgot-password', 'ProviderController@forgot_password');
    Route::post('otp-verification', 'ProviderController@otp_verification');
    Route::put('reset-password', 'ProviderController@reset_password');
});

Route::group(['prefix' => 'provider', 'as' => 'provider.', 'namespace' => 'Api\V1\Provider', 'middleware' => ['auth:api']], function () {

    Route::get('/', 'ProviderController@index');
    Route::get('dashboard', 'ProviderController@dashboard');
    Route::get('get-bank-details', 'ProviderController@get_bank_details');
    Route::put('update-bank-details', 'ProviderController@update_bank_details');

    Route::get('config', [ProviderConfigController::class, 'config'])->withoutMiddleware('auth:api');
    Route::get('info', 'ProviderController@index');
    Route::get('notifications', 'ProviderController@notifications');
    Route::put('update/fcm-token', 'ProviderController@update_fcm_token');
    Route::put('update/profile', 'ProviderController@update_profile');
    Route::get('config/get-routes', [ProviderConfigController::class, 'get_routes']);

    Route::get('subscribed/sub-categories', 'ProviderController@subscribed_sub_categories');

    Route::group(['prefix' => 'service', 'as' => 'service.',], function () {
        Route::post('update-subscription', 'ServiceController@update_subscription');
    });

    Route::group(['prefix' => 'account', 'as' => 'account.',], function () {
        Route::get('overview', 'AccountController@overview');
        Route::get('account-edit', 'AccountController@account_edit');
        Route::put('account-update', 'AccountController@account_update');
        Route::get('commission-info', 'AccountController@commission_info');
    });

    Route::resource('withdraw', 'WithdrawController', ['only' => ['index', 'store']]);
    Route::get('review', 'ProviderController@review');

    //REPORT
    Route::group(['prefix' => 'report', 'namespace' => 'Report'], function () {
        //Transaction Report
        Route::post('transaction', [TransactionReportController::class, 'get_transaction_report']);
        Route::post('transaction/download', [TransactionReportController::class, 'download_transaction_report']);

        //Booking Report
        Route::post('booking', [BookingReportController::class, 'get_booking_report']);
        Route::post('booking/download', [BookingReportController::class, 'get_booking_report_download']);

        //Business Report
        Route::group(['prefix' => 'business', 'as' => 'business.'], function () {
            Route::post('overview', [BusinessReportController::class, 'get_business_overview_report']);
            Route::post('earning', [BusinessReportController::class, 'get_business_earning_report']);
            Route::post('expense', [BusinessReportController::class, 'get_business_expense_report']);
        });
    });
});

Route::group(['prefix' => 'customer', 'as' => 'customer.', 'namespace' => 'Api\V1\Customer'], function () {
    Route::group(['prefix' => 'provider', 'as' => 'provider.'], function () {
        Route::post('list', [ProviderController::class, 'get_provider_list']);
        Route::get('list-by-sub-category', [ProviderController::class, 'get_provider_list_by_sub_category']);
    });
    Route::get('provider-details', [ProviderController::class, 'get_provider_details']);
});

//admin
Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Api\V1\Admin', 'middleware' => ['auth:api']], function () {
    Route::resource('provider', 'ProviderController', ['only' => ['index', 'store', 'edit', 'update']]);
    Route::group(['prefix' => 'provider', 'as' => 'provider.',], function () {
        Route::get('data/overview/{user_id}', 'ProviderController@overview');
        Route::put('settings/update/{provider_id}', 'ProviderController@settings_update');

        Route::put('status/update', 'ProviderController@status_update');
        Route::delete('delete', 'ProviderController@destroy');
        Route::delete('remove-image', 'ProviderController@remove_image');

        Route::get('data/reviews/{provider_id}', 'ProviderController@reviews');
        Route::get('data/requests', 'ProviderController@provider_request');
        Route::get('data/requests/search', 'ProviderController@search_request');
        Route::get('data/serviceman/list/{provider_id}', 'ProviderController@serviceman_list');

        Route::get('data/bookings/{provider_id}', 'ProviderController@bookings');
        Route::get('subscribed/sub-categories/{provider_id}', 'ProviderController@subscribed_sub_categories');
        Route::put('update-subscription/sub-categories/{provider_id}', 'ProviderController@update_subscription');
    });
});
