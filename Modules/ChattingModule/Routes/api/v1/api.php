<?php

use Illuminate\Support\Facades\Route;

//admin section
Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Api\V1\Admin', 'middleware' => ['auth:api']], function () {
    Route::group(['prefix' => 'chat'], function () {
        Route::get('channel-list', 'ChattingController@channel_list');
        Route::get('referenced-channel-list', 'ChattingController@referenced_channel_list');
        Route::post('create-channel', 'ChattingController@create_channel');
        Route::post('send-message', 'ChattingController@send_message');
        Route::get('conversation', 'ChattingController@conversation');
    });
});

//customer section
Route::group(['prefix' => 'customer', 'as' => 'customer.', 'namespace' => 'Api\V1\Customer', 'middleware' => ['auth:api']], function () {
    Route::group(['prefix' => 'chat'], function () {
        Route::get('channel-list', 'ChattingController@channel_list');
        Route::get('referenced-channel-list', 'ChattingController@referenced_channel_list');
        Route::post('create-channel', 'ChattingController@create_channel');
        Route::post('send-message', 'ChattingController@send_message');
        Route::get('conversation', 'ChattingController@conversation');
    });
});

//customer section
Route::group(['prefix' => 'provider', 'as' => 'provider.', 'namespace' => 'Api\V1\Provider', 'middleware' => ['auth:api']], function () {
    Route::group(['prefix' => 'chat'], function () {
        Route::get('channel-list', 'ChattingController@channel_list');
        Route::get('referenced-channel-list', 'ChattingController@referenced_channel_list');
        Route::post('create-channel', 'ChattingController@create_channel');
        Route::post('send-message', 'ChattingController@send_message');
        Route::get('conversation', 'ChattingController@conversation');
    });
});

//customer section
Route::group(['prefix' => 'serviceman', 'as' => 'serviceman.', 'namespace' => 'Api\V1\Serviceman', 'middleware' => ['auth:api']], function () {
    Route::group(['prefix' => 'chat'], function () {
        Route::get('channel-list', 'ChattingController@channel_list');
        Route::get('referenced-channel-list', 'ChattingController@referenced_channel_list');
        Route::post('create-channel', 'ChattingController@create_channel');
        Route::post('send-message', 'ChattingController@send_message');
        Route::get('conversation', 'ChattingController@conversation');
    });
});


//global section
Route::group(['namespace' => 'Api\V1', 'middleware' => ['auth:api']], function () {
    Route::group(['prefix' => 'chat'], function () {
        Route::get('channel-list', 'GlobalChattingController@channel_list');
        Route::get('referenced-channel-list', 'GlobalChattingController@referenced_channel_list');
        Route::post('create-channel', 'GlobalChattingController@create_channel');
        Route::post('send-message', 'GlobalChattingController@send_message');
        Route::get('conversation', 'GlobalChattingController@conversation');
        Route::get('unread-conversation', 'GlobalChattingController@unread_conversation_count');
    });
});
