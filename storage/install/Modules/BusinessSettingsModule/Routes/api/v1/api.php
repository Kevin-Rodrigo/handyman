<?php

use Illuminate\Support\Facades\Route;
use Modules\BusinessSettingsModule\Http\Controllers\Api\V1\Provider\BusinessInformationController as ProviderBusinessInformationController;


Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Api\V1\Admin', 'middleware' => ['auth:api']], function () {
    Route::group(['prefix' => 'business-settings'], function () {
        Route::get('get-business-information', 'BusinessInformationController@business_information_get');
        Route::put('set-business-information', 'BusinessInformationController@business_information_set');

        Route::get('get-service-setup', 'BusinessInformationController@service_setup_get');
        Route::put('set-service-setup', 'BusinessInformationController@service_setup_set');

        Route::get('get-pages-setup', 'BusinessInformationController@pages_setup_get');
        Route::put('set-pages-setup', 'BusinessInformationController@pages_setup_set');

        Route::get('get-notification-setting', 'ConfigurationController@notification_settings_get');
        Route::put('set-notification-setting', 'ConfigurationController@notification_settings_set');

        Route::get('get-email-config', 'ConfigurationController@email_config_get');
        Route::put('set-email-config', 'ConfigurationController@email_config_set');

        Route::get('get-third-party-config', 'ConfigurationController@third_party_config_get');
        Route::put('set-third-party-config', 'ConfigurationController@third_party_config_set');
    });
});

Route::group(['prefix' => 'provider', 'as' => 'provider.', 'middleware' => ['auth:api']], function () {
    Route::group(['prefix' => 'business-settings'], function () {
        Route::get('get-business-settings', [ProviderBusinessInformationController::class, 'business_settings_get']);
        Route::put('set-business-settings', [ProviderBusinessInformationController::class, 'business_settings_set']);
    });
});
