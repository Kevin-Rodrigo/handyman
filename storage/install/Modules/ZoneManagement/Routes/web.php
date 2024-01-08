<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Web\Admin', 'middleware' => ['admin','mpc:service_management']], function () {
    Route::group(['prefix' => 'zone', 'as' => 'zone.'], function () {
        Route::any('create', 'ZoneController@create')->name('create');
        Route::post('store', 'ZoneController@store')->name('store');
        Route::get('edit/{id}', 'ZoneController@edit')->name('edit');
        Route::put('update/{id}', 'ZoneController@update')->name('update');
        Route::put('get-active-zones/{id}', 'ZoneController@get_active_zones')->name('get-active-zones');
        Route::any('status-update/{id}', 'ZoneController@status_update')->name('status-update');
        Route::delete('delete/{id}', 'ZoneController@destroy')->name('delete');
        Route::get('download', 'ZoneController@download')->name('download');
    });
});
