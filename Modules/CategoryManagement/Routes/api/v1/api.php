<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\CategoryManagement\Http\Controllers\Api\V1\Customer\CategoryController;

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
    Route::resource('category', 'CategoryController', ['only' => ['index', 'store', 'edit', 'update']]);
    Route::put('category/status/update', 'CategoryController@status_update');
    Route::delete('category/delete', 'CategoryController@destroy');
    Route::get('category/search', 'CategoryController@search');
    Route::get('category/childes', 'CategoryController@childes');

    Route::resource('sub-category', 'SubCategoryController', ['only' => ['index', 'store', 'edit', 'update']]);
    Route::put('sub-category/status/update', 'SubCategoryController@status_update');
    Route::delete('sub-category/delete', 'SubCategoryController@destroy');
});


Route::group(['prefix' => 'provider', 'as'=>'provider.', 'namespace' => 'Api\V1\Provider','middleware'=>['auth:api']], function () {
    Route::get('sub-categories', 'CategoryController@sub_category');
    Route::resource('category', 'CategoryController', ['only' => ['index']]);

    Route::group(['prefix' => 'category', 'as'=>'category.'], function () {
        Route::get('search', 'CategoryController@search');
        Route::get('childes', 'CategoryController@childes');
    });
});


Route::group(['prefix' => 'customer', 'as'=>'customer.', 'namespace' => 'Api\V1\Customer'], function () {
    Route::group(['prefix' => 'category', 'as'=>'category.'], function () {
        Route::get('/', 'CategoryController@index');
        Route::get('childes', 'CategoryController@childes');
    });
    Route::get('sub-categories', 'SubCategoryController@index');
    Route::get('featured-categories', [CategoryController::class, 'featured']);
});
