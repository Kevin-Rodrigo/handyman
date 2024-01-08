<?php

use Illuminate\Http\Request;
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

Route::group(['prefix' => 'admin', 'as'=>'admin.', 'namespace' => 'Api\V1\Admin','middleware'=>['auth:api']], function () {
    Route::resource('transaction', 'TransactionController', ['only' => ['index', 'store']]);
});

Route::group(['prefix' => 'provider', 'as'=>'provider.', 'namespace' => 'Api\V1\Provider','middleware'=>['auth:api']], function () {
    Route::group(['prefix' => 'withdraw', 'as'=>'withdraw.'], function () {
        Route::get('methods', 'WithdrawController@get_methods');
    });
});
