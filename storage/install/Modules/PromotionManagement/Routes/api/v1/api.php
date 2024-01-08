<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Api\V1\Admin', 'middleware' => ['auth:api']], function () {
    Route::resource('discount', 'DiscountController', ['only' => ['index', 'store', 'edit', 'update']]);
    Route::group(['prefix' => 'discount', 'as' => 'discount.',], function () {
        Route::put('status/update', 'DiscountController@status_update');
        Route::delete('delete', 'DiscountController@destroy');
    });

    Route::resource('coupon', 'CouponController', ['only' => ['index', 'store', 'edit', 'update']]);
    Route::group(['prefix' => 'coupon', 'as' => 'coupon.',], function () {
        Route::get('config', 'CouponController@config');
        Route::put('status/update', 'CouponController@status_update');
        Route::delete('delete', 'CouponController@destroy');
    });

    Route::resource('campaign', 'CampaignController', ['only' => ['index', 'store', 'edit', 'update']]);
    Route::group(['prefix' => 'campaign', 'as' => 'campaign.',], function () {
        Route::put('status/update', 'CampaignController@status_update');
        Route::delete('delete', 'CampaignController@destroy');
    });

    Route::resource('banner', 'BannerController', ['only' => ['index', 'store', 'edit', 'update']]);
    Route::group(['prefix' => 'banner', 'as' => 'banner.',], function () {
        Route::put('status/update', 'BannerController@status_update');
        Route::delete('delete', 'BannerController@destroy');
    });

    Route::resource('push-notification', 'PushNotificationController', ['only' => ['index', 'store', 'edit', 'update']]);
    Route::group(['prefix' => 'push-notification', 'as' => 'push-notification.',], function () {
        Route::put('status/update', 'PushNotificationController@status_update');
        Route::delete('delete', 'PushNotificationController@destroy');
    });
});

Route::group(['prefix' => 'customer', 'as' => 'customer.', 'namespace' => 'Api\V1\Customer'], function () {
    Route::group(['prefix' => 'banner', 'as' => 'banner.',], function () {
        Route::get('/', 'BannerController@index');
    });

    Route::group(['prefix' => 'notification', 'as' => 'notification.',], function () {
        Route::get('/', 'NotificationController@index');
    });

    Route::resource('coupon', 'CouponController', ['only' => ['index']]);
    Route::group(['prefix' => 'coupon', 'as' => 'coupon.'], function () {
        Route::get('remove', 'CouponController@remove_coupon');
        Route::post('apply', 'CouponController@apply_coupon');
    });

    Route::resource('campaign', 'CampaignController', ['only' => ['index']]);
    Route::group(['prefix' => 'campaign', 'as' => 'campaign.', 'middleware' => ['auth:api']], function () {
        Route::get('data/items', 'CampaignController@campaign_items')->withoutMiddleware('auth:api');
    });
});
