<?php

use Illuminate\Support\Facades\Route;
use Modules\BookingModule\Http\Controllers\Api\V1\Customer\BookingController;
use Modules\BookingModule\Http\Controllers\Api\V1\Provider\BookingController as ProviderBookingController;
use Modules\BookingModule\Http\Controllers\Api\V1\Serviceman\BookingController as ServicemanBookingController;

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

Route::group(['prefix' => 'customer', 'as' => 'customer.', 'namespace' => 'Api\V1\Customer', 'middleware' => ['auth:api']], function () {
    Route::group(['prefix'=>'booking','as'=>'booking.'],function (){
        Route::get('/', [BookingController::class, 'index']);
        Route::get('/{booking_id}', [BookingController::class, 'show']);
        Route::post('request/send', [BookingController::class, 'place_request'])->middleware('hitLimiter')->withoutMiddleware('auth:api');
        Route::put('status-update/{booking_id}', [BookingController::class, 'status_update']);

        Route::post('track/{readable_id}', [BookingController::class, 'track'])->withoutMiddleware('auth:api');
    });
});


Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Api\V1\Admin', 'middleware' => ['auth:api']], function () {
    Route::group(['prefix'=>'booking','as'=>'booking.'],function (){
        Route::post('/', 'BookingController@index');
        Route::get('{id}', 'BookingController@show');
        Route::put('status-update/{booking_id}', 'BookingController@status_update');
        Route::put('schedule-update/{booking_id}', 'BookingController@schedule_update');
        Route::get('data/download', 'BookingController@download');
    });
});


Route::group(['prefix' => 'provider', 'as' => 'provider.', 'namespace' => 'Api\V1\Provider', 'middleware' => ['auth:api']], function () {
    Route::group(['prefix'=>'booking','as'=>'booking.'],function (){
        Route::post('/', 'BookingController@index');
        Route::get('{id}', 'BookingController@show');
        Route::put('request-accept/{booking_id}', 'BookingController@request_accept');
        Route::put('status-update/{booking_id}', 'BookingController@status_update');
        Route::put('schedule-update/{booking_id}', 'BookingController@schedule_update');
        Route::put('assign-serviceman/{booking_id}', 'BookingController@assign_serviceman');
        Route::get('data/download', 'BookingController@download');

        Route::get('opt/notification-send', 'BookingController@notification_send');

        //edit
        Route::get('service/info', [ProviderBookingController::class, 'get_service_info']);
        Route::put('service/edit/update-booking', [ProviderBookingController::class, 'update_booking']);
        Route::put('service/edit/remove-service', [ProviderBookingController::class, 'remove_service']);
    });
});


Route::group(['prefix' => 'serviceman', 'as' => 'serviceman.', 'namespace' => 'Api\V1\Serviceman', 'middleware' => ['auth:api']], function () {
    Route::group(['prefix'=>'booking','as'=>'booking.'],function (){
        Route::put('status-update/{booking_id}', 'BookingController@status_update');
        Route::put('payment-status-update/{booking_id}', 'BookingController@payment_status_update');
        Route::get('list', 'BookingController@booking_list');
        Route::get('detail/{id}', 'BookingController@booking_details');

        Route::get('opt/notification-send', 'BookingController@notification_send');

        //edit
        Route::get('service/info', [ServicemanBookingController::class, 'get_service_info']);
        Route::put('service/edit/update-booking', [ServicemanBookingController::class, 'update_booking']);
        Route::put('service/edit/remove-service', [ServicemanBookingController::class, 'remove_service']);
    });
});
