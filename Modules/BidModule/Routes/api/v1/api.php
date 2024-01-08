<?php

use Illuminate\Support\Facades\Route;
use Modules\BidModule\Http\Controllers\APi\V1\Customer\PostBidController;
use Modules\BidModule\Http\Controllers\APi\V1\Customer\PostController;
use Modules\BidModule\Http\Controllers\APi\V1\Provider\PostBidController as ProviderPostBidController;
use Modules\BidModule\Http\Controllers\APi\V1\Provider\PostController as ProviderPostController;

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

/** Customer */
Route::group(['prefix' => 'customer', 'namespace' => 'Api\V1\Customer', 'middleware' => ['auth:api', 'ensureBiddingIsActive']], function () {
    Route::group(['prefix' => 'post'], function () {
        Route::get('/', [PostController::class, 'index']);
        Route::get('/details/{id}', [PostController::class, 'show']);
        Route::post('/', [PostController::class, 'store']);

        Route::put('update-info', [PostController::class, 'update_info']);

        //Bids
        Route::group(['prefix' => 'bid'], function () {
            Route::get('/', [PostBidController::class, 'index']);
            Route::get('details', [PostBidController::class, 'show']);
            Route::put('update-status', [PostBidController::class, 'update']);
        });
    });
});

/** Provider */
Route::group(['prefix' => 'provider', 'namespace' => 'Api\V1\Provider', 'middleware' => ['auth:api', 'ensureBiddingIsActive']], function () {
    Route::group(['prefix' => 'post'], function () {
        Route::get('/', [ProviderPostController::class, 'index']);
        Route::get('details/{id}', [ProviderPostController::class, 'show']);
        Route::post('/', [ProviderPostController::class, 'decline']);  //decline posts

        //Bids
        Route::group(['prefix' => 'bid'], function () {
            Route::get('/', [ProviderPostBidController::class, 'index']);
            Route::post('/', [ProviderPostBidController::class, 'store']);  //make bid for post
            Route::post('/withdraw', [ProviderPostBidController::class, 'withdraw']);  //make bid for post
        });
    });
});
