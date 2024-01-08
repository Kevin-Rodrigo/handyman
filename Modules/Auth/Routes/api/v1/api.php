<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//admin routes
Route::group(['prefix' => 'admin', 'as' => 'admin', 'namespace' => 'Api\V1'], function () {

    Route::group(['prefix' => 'auth', 'as' => 'auth.'], function () {
        Route::post('login', 'LoginController@admin_login')->name('login');
    });

});

//provider routes
Route::group(['prefix' => 'provider', 'as' => 'provider', 'namespace' => 'Api\V1'], function () {

    Route::group(['prefix' => 'auth', 'as' => 'auth.'], function () {
        Route::post('registration', 'RegisterController@provider_register')->name('registration');
        Route::post('login', 'LoginController@provider_login')->name('login');
    });

});

//customer routes
Route::group(['prefix' => 'customer', 'as' => 'customer', 'namespace' => 'Api\V1'], function () {
    Route::group(['prefix' => 'auth', 'as' => 'auth.'], function () {
        Route::post('registration', 'RegisterController@customer_register')->name('registration');
        Route::post('login', 'LoginController@customer_login')->name('login');
        Route::post('social-login', 'LoginController@social_customer_login')->name('social-login');
        Route::post('logout', 'LoginController@customer_logout')->middleware('auth:api');
    });
});

//serviceman routes
Route::group(['prefix' => 'serviceman', 'as' => 'serviceman', 'namespace' => 'Api\V1'], function () {
    Route::group(['prefix' => 'auth', 'as' => 'auth.'], function () {
        Route::post('login', 'LoginController@serviceman_login')->name('login');
    });
});


Route::group(['prefix' => 'user', 'as' => 'user.', 'middleware' => ['auth:api'], 'namespace' => 'Api\V1'], function () {
    Route::post('logout', 'LoginController@logout')->name('logout');
});

