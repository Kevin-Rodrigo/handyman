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
use Modules\BusinessSettingsModule\Http\Controllers\Web\Admin\BusinessInformationController;
use Modules\BusinessSettingsModule\Http\Controllers\Web\Provider\BusinessInformationController as ProviderBusinessInformationController;
use Modules\BusinessSettingsModule\Http\Controllers\Web\Admin\ConfigurationController;
use Modules\CustomerModule\Http\Controllers\Web\Admin\CustomerController;

Route::group(['namespace' => 'Api\V1\Admin'], function () {
    Route::get('file-manager', 'FileManagerController@index');
});


Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Web\Admin', 'middleware' => ['admin','mpc:system_management']], function () {
    Route::group(['prefix' => 'business-settings', 'as' => 'business-settings.'], function () {
        Route::get('get-business-information', 'BusinessInformationController@business_information_get')->name('get-business-information');
        Route::put('set-business-information', 'BusinessInformationController@business_information_set')->name('set-business-information');

        Route::put('set-otp-login-information', [BusinessInformationController::class, 'otp_login_information_set'])->name('set-otp-login-information');

        Route::put('set-bidding-system', 'BusinessInformationController@set_bidding_system')->name('set-bidding-system');

        Route::put('update-action-status', 'BusinessInformationController@update_action_status')->name('update-action-status');
        Route::put('set-promotion-setup', 'BusinessInformationController@promotion_setup_set')->name('set-promotion-setup');
        Route::put('set-customer-setup', 'BusinessInformationController@customer_setup')->name('set-customer-setup');
        Route::put('set-provider-setup', 'BusinessInformationController@provider_setup')->name('set-provider-setup');
        Route::put('set-service-setup', 'BusinessInformationController@service_setup')->name('set-service-setup');
        Route::put('set-servicemen', 'BusinessInformationController@servicemen')->name('set-servicemen');

        Route::put('set-booking-setup', 'BusinessInformationController@booking_setup_set')->name('set-booking-setup');

        Route::get('get-pages-setup', 'BusinessInformationController@pages_setup_get')->name('get-pages-setup');
        Route::post('set-pages-setup', 'BusinessInformationController@pages_setup_set')->name('set-pages-setup');

        //Gallery
        Route::get('get-gallery-setup/{folder_path?}', [BusinessInformationController::class, 'gallery_setup_get'])->name('get-gallery-setup');
        Route::post('/image-upload', [BusinessInformationController::class, 'gallery_image_upload'])->name('upload-gallery-image');
        Route::get('/image-download/{file_name}', [BusinessInformationController::class, 'gallery_image_download'])->name('download-gallery-image');
        Route::delete('/delete/{file_path}', [BusinessInformationController::class, 'gallery_image_remove'])->name('remove-gallery-image');
        Route::get('download/public', [BusinessInformationController::class, 'download_public_directory'])->name('download.public');

        //database backup
        Route::get('get-database-backup', [BusinessInformationController::class, 'get_database_backup'])->name('get-database-backup');
        Route::get('backup-database-backup', [BusinessInformationController::class, 'backup_database'])->name('backup-database-backup');
        Route::get('delete-database-backup/{file_name}', [BusinessInformationController::class, 'delete_database_backup'])->name('delete-database-backup');
        Route::get('restore-database-backup/{file_name}', [BusinessInformationController::class, 'restore_database_backup'])->name('restore-database-backup');
        Route::get('download-database-backup/{file_name}', [BusinessInformationController::class, 'download'])->name('download-database-backup');

        Route::get('get-landing-information', 'LandingPageController@landing_information_get')->name('get-landing-information');
        Route::put('set-landing-information', 'LandingPageController@landing_information_set')->name('set-landing-information');
        Route::delete('delete-landing-information/{page}/{id}', 'LandingPageController@landing_information_delete')->name('delete-landing-information');
    });

    Route::group(['prefix' => 'configuration', 'as' => 'configuration.'], function () {
        Route::get('get-notification-setting', 'ConfigurationController@notification_settings_get')->name('get-notification-setting');
        Route::put('set-notification-setting', 'ConfigurationController@notification_settings_set')->name('set-notification-setting');
        Route::any('set-message-setting', 'ConfigurationController@message_settings_set')->name('set-message-setting');

        Route::get('get-email-config', 'ConfigurationController@email_config_get')->name('get-email-config');
        Route::put('set-email-config', 'ConfigurationController@email_config_set')->name('set-email-config');

        Route::get('get-third-party-config', 'ConfigurationController@third_party_config_get')->name('get-third-party-config');
        Route::put('set-third-party-config', 'ConfigurationController@third_party_config_set')->name('set-third-party-config');

        Route::get('get-app-settings', 'ConfigurationController@app_settings_config_get')->name('get-app-settings');
        Route::put('set-app-settings', 'ConfigurationController@app_settings_config_set')->name('set-app-settings');

        Route::put('social-login-config-set', [ConfigurationController::class, 'social_login_config_set'])->name('social-login-config-set');
    });

    Route::group(['prefix' => 'customer', 'as' => 'customer.'], function () {
        Route::get('settings', [ConfigurationController::class, 'get_customer_settings'])->name('settings');
        Route::put('settings', [ConfigurationController::class, 'set_customer_settings']);
    });
});

Route::group(['prefix' => 'provider', 'as' => 'provider.', 'namespace' => 'Web\Provider', 'middleware' => ['provider']], function () {
    Route::group(['prefix' => 'business-settings', 'as' => 'business-settings.'], function () {
        Route::get('get-business-information', [ProviderBusinessInformationController::class, 'business_information_get'])->name('get-business-information');
        Route::put('set-business-information', [ProviderBusinessInformationController::class, 'business_information_set'])->name('set-business-information');
    });
});
