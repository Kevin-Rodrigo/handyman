<?php

use Illuminate\Support\Facades\Route;
use Modules\BidModule\Http\Controllers\Web\Provider\PostController;
use Modules\BidModule\Http\Controllers\Web\Admin\PostController as AdminPostController;

Route::group(['prefix' => 'provider', 'as' => 'provider.', 'namespace' => 'Web\Provider', 'middleware' => ['provider']], function () {

    Route::group(['prefix' => 'booking', 'as' => 'booking.'], function () {
        Route::group(['prefix' => 'post', 'as' => 'post.'], function () {
            Route::any('/', [PostController::class, 'index'])->name('list');
            Route::any('export', [PostController::class, 'export'])->name('export');
            Route::any('details/{id}', [PostController::class, 'details'])->name('details');
            Route::any('update-status/{id}', [PostController::class, 'update_status'])->name('update_status');
            Route::post('multi-ignore', [PostController::class, 'multi_ignore'])->name('multi-ignore');
            Route::any('withdraw/{id}', [PostController::class, 'withdraw'])->name('withdraw');

            Route::any('check-all', [PostController::class, 'check_all'])->name('check_all');
        });
    });
});

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Web\Admin', 'middleware' => ['admin']], function () {

    Route::group(['prefix' => 'booking', 'as' => 'booking.'], function () {
        Route::group(['prefix' => 'post', 'as' => 'post.'], function () {
            Route::any('/', [AdminPostController::class, 'index'])->name('list');
            Route::any('export', [AdminPostController::class, 'export'])->name('export');
            Route::any('details/{id}', [AdminPostController::class, 'details'])->name('details');
            Route::any('delete/{id}', [AdminPostController::class, 'delete'])->name('delete');
            Route::post('multi-remove', [AdminPostController::class, 'multi_delete'])->name('multi-remove');
        });
    });
});
