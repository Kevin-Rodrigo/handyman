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

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Web\Admin', 'middleware' => ['admin']], function () {
    Route::group(['prefix' => 'chat', 'as' => 'chat.'], function () {
        Route::get('index', 'ChattingController@index')->name('index');
        Route::get('channel-list', 'ChattingController@channel_list');
        Route::get('referenced-channel-list', 'ChattingController@referenced_channel_list');
        Route::post('create-channel', 'ChattingController@create_channel')->name('create-channel');
        Route::post('send-message', 'ChattingController@send_message')->name('send-message');
        Route::get('ajax-conversation', 'ChattingController@conversation')->name('ajax-conversation');
    });
});

Route::group(['prefix' => 'provider', 'as' => 'provider.', 'namespace' => 'Web\Provider', 'middleware' => ['provider']], function () {
    Route::group(['prefix' => 'chat', 'as' => 'chat.'], function () {
        Route::get('index', 'ChattingController@index')->name('index');
        Route::get('channel-list', 'ChattingController@channel_list');
        Route::get('referenced-channel-list', 'ChattingController@referenced_channel_list');
        Route::post('create-channel', 'ChattingController@create_channel')->name('create-channel');
        Route::post('send-message', 'ChattingController@send_message')->name('send-message');
        Route::get('ajax-conversation', 'ChattingController@conversation')->name('ajax-conversation');
    });
});
