<?php

use Illuminate\Support\Facades\Route;
use Modules\ServiceManagement\Http\Controllers\Web\Admin\ServiceRequestController;
use Modules\ServiceManagement\Http\Controllers\Web\Provider\ServiceController;

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Web\Admin', 'middleware' => ['admin','mpc:service_management']], function () {

    Route::group(['prefix' => 'service', 'as' => 'service.'], function () {
        Route::any('list', 'ServiceController@index')->name('index');
        Route::any('create', 'ServiceController@create')->name('create');
        Route::post('store', 'ServiceController@store')->name('store');
        Route::any('detail/{id}', 'ServiceController@show')->name('detail');
        Route::get('edit/{id}', 'ServiceController@edit')->name('edit');
        Route::put('update/{id}', 'ServiceController@update')->name('update');
        Route::any('status-update/{id}', 'ServiceController@status_update')->name('status-update');
        Route::delete('delete/{id}', 'ServiceController@destroy')->name('delete');
        Route::any('download', 'ServiceController@download')->name('download');

        Route::get('request/list', [ServiceRequestController::class, 'request_list'])->name('request.list');
        Route::post('request/update/{id}', [ServiceRequestController::class, 'update_status'])->name('request.update');

        //ajax routes
        Route::any('ajax-add-variant', 'ServiceController@ajax_add_variant')->name('ajax-add-variant')->withoutMiddleware('csrf');
        Route::any('ajax-remove-variant/{variant_key}', 'ServiceController@ajax_remove_variant')->name('ajax-remove-variant')->withoutMiddleware('csrf');
        Route::any('ajax-delete-db-variant/{variant_key}/{service_id}', 'ServiceController@ajax_delete_db_variant')->name('ajax-delete-db-variant')->withoutMiddleware('csrf');
    });

    Route::group(['prefix' => 'faq', 'as' => 'faq.'], function () {
        Route::post('store/{service_id}', 'FAQController@store')->name('store');
        Route::get('edit/{id}', 'FAQController@edit')->name('edit');
        Route::any('update/{id}', 'FAQController@update')->name('update');
        Route::any('status-update/{id}', 'FAQController@status_update')->name('status-update');
        Route::any('delete/{id}/{service_id}', 'FAQController@destroy')->name('delete');
    });
});


Route::group(['prefix' => 'provider', 'as' => 'provider.', 'namespace' => 'Web\Provider', 'middleware' => ['provider']], function () {
    Route::group(['prefix' => 'service', 'as' => 'service.'], function () {
        Route::get('available', 'ServiceController@index')->name('available');
        Route::get('request-list', [ServiceController::class, 'request_list'])->name('request-list');
        Route::get('make-request', [ServiceController::class, 'make_request'])->name('make-request');
        Route::post('make-request', [ServiceController::class, 'store_request']);
        Route::put('update-subscription', 'ServiceController@update_subscription')->name('update-subscription');
        Route::any('detail/{id}', 'ServiceController@show')->name('detail');
    });
});
