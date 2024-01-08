<?php

use Illuminate\Support\Facades\Route;
use Modules\ServicemanModule\Http\Controllers\Api\V1\Serviceman\ConfigController as ServicemanConfigController;

//provider routes
Route::group(['prefix' => 'provider', 'as' => 'provider', 'namespace' => 'Api\V1\Provider', 'middleware' => ['auth:api']], function () {

    //serviceman
    Route::resource('serviceman', 'ServicemanController', ['only' => ['index', 'store', 'edit', 'update', 'show']]);
    Route::group(['prefix' => 'serviceman', 'as' => 'serviceman.'], function () {
        Route::delete('delete', 'ServicemanController@destroy');
        Route::put('status/update', 'ServicemanController@change_active_status');
    });

});

//customer section
Route::group(['prefix' => 'serviceman', 'as' => 'serviceman.', 'namespace' => 'Api\V1\Serviceman'], function () {

    Route::post('forgot-password', 'ServicemanController@forgot_password');
    Route::post('otp-verification', 'ServicemanController@otp_verification');
    Route::put('reset-password', 'ServicemanController@reset_password');

    Route::group(['middleware' => ['auth:api']], function () {
        Route::get('dashboard', 'ServicemanController@dashboard');

        Route::group(['prefix' => 'config'], function () {
            Route::get('/', 'ConfigController@configuration')->withoutMiddleware('auth:api');
            Route::get('get-zone-id', 'ConfigController@get_zone');
            Route::get('place-api-autocomplete', 'ConfigController@place_api_autocomplete');
            Route::get('distance-api', 'ConfigController@distance_api');
            Route::get('place-api-details', 'ConfigController@place_api_details');
            Route::get('geocode-api', 'ConfigController@geocode_api');
            Route::get('get-routes', [ServicemanConfigController::class, 'get_routes']);
        });

        Route::get('info', 'ServicemanController@index');
        Route::put('update/profile', 'ServicemanController@update_profile');
        Route::put('update/fcm-token', 'ServicemanController@update_fcm_token');
        Route::get('push-notifications', 'ServicemanController@push_notifications');

        Route::group(['prefix' => 'profile', 'middleware' => ['auth:api']], function () {
            Route::put('info', 'ServicemanController@profile_info');
            Route::put('change-password', 'ServicemanController@change_password');
        });
    });
});

