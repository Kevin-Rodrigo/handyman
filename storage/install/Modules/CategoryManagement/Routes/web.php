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

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Web\Admin', 'middleware' => ['admin','mpc:service_management']], function () {

    Route::group(['prefix' => 'category', 'as' => 'category.'], function () {
        Route::any('create', 'CategoryController@create')->name('create');
        Route::post('store', 'CategoryController@store')->name('store');
        Route::get('edit/{id}', 'CategoryController@edit')->name('edit');
        Route::put('update/{id}', 'CategoryController@update')->name('update');
        Route::any('status-update/{id}', 'CategoryController@status_update')->name('status-update');
        Route::any('featured-update/{id}', 'CategoryController@featured_update')->name('featured-update');
        Route::delete('delete/{id}', 'CategoryController@destroy')->name('delete');
        Route::get('childes', 'CategoryController@childes');
        Route::get('ajax-childes/{id}', 'CategoryController@ajax_childes')->name('ajax-childes');
        Route::get('ajax-childes-only/{id}', 'CategoryController@ajax_childes_only')->name('ajax-childes-only');
        Route::get('download', 'CategoryController@download')->name('download');
    });

    Route::group(['prefix' => 'sub-category', 'as' => 'sub-category.'], function () {
        Route::any('create', 'SubCategoryController@create')->name('create');
        Route::post('store', 'SubCategoryController@store')->name('store');
        Route::get('edit/{id}', 'SubCategoryController@edit')->name('edit');
        Route::put('update/{id}', 'SubCategoryController@update')->name('update');
        Route::any('status-update/{id}', 'SubCategoryController@status_update')->name('status-update');
        Route::delete('delete/{id}', 'SubCategoryController@destroy')->name('delete');
        Route::get('download', 'SubCategoryController@download')->name('download');
    });
});
