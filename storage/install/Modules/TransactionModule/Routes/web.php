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
use Modules\TransactionModule\Http\Controllers\Web\Admin\WithdrawnController;
use Modules\TransactionModule\Http\Controllers\Web\Admin\WithdrawRequestController;


Route::group(['prefix' => 'admin', 'as'=>'admin.', 'namespace' => 'Web\Admin','middleware'=>['admin']], function () {

    Route::group(['prefix' => 'transaction', 'as'=>'transaction.'], function () {
        Route::any('list', 'TransactionController@index')->name('list');
        Route::any('download', 'TransactionController@download')->name('download');
    });

    Route::group(['prefix' => 'withdraw', 'as'=>'withdraw.'], function () {
        Route::group(['prefix' => 'request', 'as'=>'request.'], function () {
            Route::any('list', [WithdrawRequestController::class, 'index'])->name('list');
            Route::any('download', [WithdrawRequestController::class, 'download'])->name('download');
            Route::post('import', [WithdrawRequestController::class, 'import'])->name('import');
            Route::post('update-status/{id}', [WithdrawRequestController::class, 'update_status'])->name('update_status');
            Route::put('update-multiple-status', [WithdrawRequestController::class, 'update_multiple_status'])->name('update_multiple_status');
        });

        Route::group(['prefix' => 'method', 'as'=>'method.'], function () {
            Route::any('list', [WithdrawnController::class, 'method_list'])->name('list');
            Route::get('create', 'WithdrawnController@method_create')->name('create');
            Route::post('store', 'WithdrawnController@method_store')->name('store');
            Route::get('edit/{id}', 'WithdrawnController@method_edit')->name('edit');
            Route::put('update', 'WithdrawnController@method_update')->name('update');
            Route::delete('delete/{id}', 'WithdrawnController@method_destroy')->name('delete');
            Route::any('status-update/{id}', 'WithdrawnController@method_status_update')->name('status-update');
            Route::any('default-status-update/{id}', 'WithdrawnController@method_default_status_update')->name('default-status-update');
        });
    });

});
Route::group(['prefix' => 'provider', 'as'=>'provider.', 'namespace' => 'Web\Provider','middleware'=>['provider']], function () {
    Route::group(['prefix' => 'withdraw', 'as'=>'withdraw.'], function () {
        Route::group(['prefix' => 'method', 'as'=>'method.'], function () {
            Route::get('list', 'WithdrawController@get_method')->name('list');
        });
    });

});
