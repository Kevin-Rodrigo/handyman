<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Web\Admin', 'middleware' => ['admin','mpc:promotion_management']], function () {

    Route::group(['prefix' => 'discount', 'as' => 'discount.'], function () {
        Route::any('create', 'DiscountController@create')->name('create');
        Route::any('list', 'DiscountController@index')->name('list');
        Route::post('store', 'DiscountController@store')->name('store');
        Route::get('edit/{id}', 'DiscountController@edit')->name('edit');
        Route::put('update/{id}', 'DiscountController@update')->name('update');
        Route::any('status-update/{id}', 'DiscountController@status_update')->name('status-update');
        Route::delete('delete/{id}', 'DiscountController@destroy')->name('delete');
        Route::any('download', 'DiscountController@download')->name('download');
    });

    Route::group(['prefix' => 'coupon', 'as' => 'coupon.'], function () {
        Route::any('create', 'CouponController@create')->name('create');
        Route::any('list', 'CouponController@index')->name('list');
        Route::post('store', 'CouponController@store')->name('store');
        Route::get('edit/{id}', 'CouponController@edit')->name('edit');
        Route::put('update/{id}', 'CouponController@update')->name('update');
        Route::any('status-update/{id}', 'CouponController@status_update')->name('status-update');
        Route::delete('delete/{id}', 'CouponController@destroy')->name('delete');
        Route::any('download', 'CouponController@download')->name('download');
    });

    Route::group(['prefix' => 'campaign', 'as' => 'campaign.'], function () {
        Route::any('create', 'CampaignController@create')->name('create');
        Route::any('list', 'CampaignController@index')->name('list');
        Route::post('store', 'CampaignController@store')->name('store');
        Route::get('edit/{id}', 'CampaignController@edit')->name('edit');
        Route::put('update/{id}', 'CampaignController@update')->name('update');
        Route::any('status-update/{id}', 'CampaignController@status_update')->name('status-update');
        Route::delete('delete/{id}', 'CampaignController@destroy')->name('delete');
        Route::any('download', 'CampaignController@download')->name('download');
    });

    Route::group(['prefix' => 'banner', 'as' => 'banner.'], function () {
        Route::any('create', 'BannerController@create')->name('create');
        Route::post('store', 'BannerController@store')->name('store');
        Route::get('edit/{id}', 'BannerController@edit')->name('edit');
        Route::put('update/{id}', 'BannerController@update')->name('update');
        Route::any('status-update/{id}', 'BannerController@status_update')->name('status-update');
        Route::delete('delete/{id}', 'BannerController@destroy')->name('delete');
        Route::any('download', 'BannerController@download')->name('download');
    });

    Route::group(['prefix' => 'push-notification', 'as' => 'push-notification.'], function () {
        Route::any('create', 'PushNotificationController@create')->name('create');
        Route::post('store', 'PushNotificationController@store')->name('store');
        Route::get('edit/{id}', 'PushNotificationController@edit')->name('edit');
        Route::put('update/{id}', 'PushNotificationController@update')->name('update');
        Route::any('status-update/{id}', 'PushNotificationController@status_update')->name('status-update');
        Route::delete('delete/{id}', 'PushNotificationController@destroy')->name('delete');
        Route::any('download', 'PushNotificationController@download')->name('download');
    });

});

