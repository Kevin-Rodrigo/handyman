<?php

use Illuminate\Support\Facades\Route;

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

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Api\V1\Admin', 'middleware' => ['auth:api']], function () {
    Route::resource('zone', 'ZoneController', ['only' => ['index', 'store', 'edit', 'update']]);
    Route::put('zone/status/update', 'ZoneController@status_update');
    Route::delete('zone/delete', 'ZoneController@destroy');
});

//public
Route::get('zones', 'PublicZoneController@index');
