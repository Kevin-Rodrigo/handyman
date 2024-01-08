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

Route::group(['prefix' => 'admin', 'as'=>'admin.', 'namespace' => 'Web\Admin','middleware'=>['admin','mpc:system_management']], function () {
    Route::group(['prefix'=>'configuration', 'as'=>'configuration.'],function (){
        Route::get('sms-get', 'SMSConfigController@sms_config_get')->name('sms-get');
        Route::put('sms-set', 'SMSConfigController@sms_config_set')->name('sms-set');
    });
});
