<?php

use Illuminate\Support\Facades\Route;
use Modules\AddonModule\Http\Controllers\Web\Admin\AddonController;

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

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Web\Admin', 'middleware' => ['admin']], function () {

    Route::group(['prefix' => 'addon', 'as' => 'addon.'], function () {
        Route::get('/', [AddonController::class, 'index'])->name('index');
        Route::post('publish', [AddonController::class, 'publish'])->name('publish');
        Route::post('activation', [AddonController::class, 'activation'])->name('activation');
        Route::post('upload', [AddonController::class, 'upload'])->name('upload');
        Route::post('delete', [AddonController::class, 'delete_theme'])->name('delete');
    });
});
