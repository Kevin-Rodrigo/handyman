<?php

use Illuminate\Support\Facades\Route;
use Modules\CartModule\Http\Controllers\Api\V1\Customer\CartController;


Route::group(['prefix' => 'customer', 'as' => 'customer.', 'namespace' => 'Api\V1\Customer'], function () {
    Route::group(['prefix' => 'cart', 'as' => 'cart.',], function () {
        Route::post('add', [CartController::class, 'add_to_cart']);
        Route::get('list', [CartController::class, 'list']);
        Route::put('update-quantity/{id}', [CartController::class, 'update_qty']);
        Route::put('update/provider', [CartController::class, 'update_provider']);
        Route::delete('remove/{id}', [CartController::class, 'remove']);
        Route::delete('data/empty', [CartController::class, 'empty_cart']);
    });
});

