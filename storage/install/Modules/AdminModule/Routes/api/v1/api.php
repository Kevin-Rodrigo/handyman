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
    Route::get('config', 'ConfigController@config')->withoutMiddleware('auth:api');
    Route::get('counts', 'ConfigController@counts');
    Route::get('dashboard', 'AdminController@dashboard');

    Route::get('info', 'AdminController@index');
    Route::get('/', 'AdminController@edit');
    Route::put('update/profile', 'AdminController@update_profile');

    Route::resource('role', 'RoleController', ['only' => ['index', 'store', 'edit', 'update', 'destroy']]);
    Route::group(['prefix' => 'role', 'as' => 'role.',], function () {
        Route::put('status/update', 'RoleController@status_update');
        Route::get('search', 'RoleController@search');
        Route::delete('delete', 'RoleController@destroy');
    });

    Route::resource('employee', 'EmployeeController', ['only' => ['index', 'store', 'edit', 'update']]);
    Route::group(['prefix' => 'employee', 'as' => 'employee.',], function () {
        Route::put('status/update', 'EmployeeController@status_update');
        Route::get('search', 'EmployeeController@search');
        Route::delete('delete', 'EmployeeController@destroy');
    });

    Route::resource('withdraw', 'WithdrawController', ['only' => ['index', 'update']]);

    Route::group(['prefix' => 'provider', 'as' => 'provider.',], function () {
        Route::post('collect-cash', 'CollectCashController@collect_cash');
        Route::get('collect-cash-transaction', 'CollectCashController@collect_cash_transaction');
    });
});


Route::get('test', function () {
    $collection = collect(['']);

    $matrix = $collection->crossJoin(['a', 'b', 'c'], ['I', 'II'],['q','w']);

    return $matrix->all();
});
