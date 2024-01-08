<?php

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Modules\Gateways\Http\Controllers\PaymobController;
use Modules\PaymentModule\Http\Controllers\PaytmController;
use Modules\Gateways\Http\Controllers\MercadoPagoController;
use Modules\PaymentModule\Http\Controllers\PaymentController;
use Modules\PaymentModule\Http\Controllers\PaystackController;
use Modules\PaymentModule\Http\Controllers\RazorPayController;
use Modules\PaymentModule\Http\Controllers\SenangPayController;
use Modules\PaymentModule\Http\Controllers\FlutterwaveV3Controller;
use Modules\PaymentModule\Http\Controllers\StripePaymentController;
use Modules\PaymentModule\Http\Controllers\Web\Admin\BonusController;
use Modules\PaymentModule\Http\Controllers\SslCommerzPaymentController;
use Modules\PaymentModule\Http\Controllers\Web\Admin\PaymentConfigController;


/** Payment */

$is_published = 0;
try {
    $full_data = include('Modules/Gateways/Addon/info.php');
    $is_published = $full_data['is_published'] == 1 ? 1 : 0;
} catch (\Exception $exception) {}


Route::get('payment', [PaymentController::class, 'index']);

if (!$is_published) {
    Route::group(['prefix' => 'payment'], function () {
        Route::get('/', [PaymentController::class, 'index']);

        //SSLCOMMERZ
        Route::group(['prefix' => 'sslcommerz', 'as' => 'sslcommerz.'], function () {
            Route::get('pay', [SslCommerzPaymentController::class, 'index'])->name('pay');
            Route::post('success', [SslCommerzPaymentController::class, 'success']);
            Route::post('failed', [SslCommerzPaymentController::class, 'failed']);
            Route::post('canceled', [SslCommerzPaymentController::class, 'canceled']);
        });

        //STRIPE
        Route::group(['prefix' => 'stripe', 'as' => 'stripe.'], function () {
            Route::get('pay', [StripePaymentController::class, 'index'])->name('pay');
            Route::get('token', [StripePaymentController::class, 'payment_process_3d'])->name('token');
            Route::get('success', [StripePaymentController::class, 'success'])->name('success');
        });

        //RAZOR-PAY
        Route::group(['prefix' => 'razor-pay', 'as' => 'razor-pay.'], function () {
            Route::get('pay', [RazorPayController::class, 'index']);
            Route::post('payment', [RazorPayController::class, 'payment'])->name('payment');
        });

        //SENANG-PAY
        Route::group(['prefix' => 'senang-pay', 'as' => 'senang-pay.'], function () {
            Route::get('pay', [SenangPayController::class, 'index']);
            Route::any('callback', [SenangPayController::class, 'return_senang_pay']);
        });

        //PAYTM
        Route::group(['prefix' => 'paytm', 'as' => 'paytm.'], function () {
            Route::get('pay', [PaytmController::class, 'payment']);
            Route::any('response', [PaytmController::class, 'response'])->name('response');
        });

        //FLUTTERWAVE
        Route::group(['prefix' => 'flutterwave-v3', 'as' => 'flutterwave-v3.'], function () {
            Route::get('pay', [FlutterwaveV3Controller::class, 'initialize'])->name('pay');
            Route::get('callback', [FlutterwaveV3Controller::class, 'callback'])->name('callback');
        });

        //PAYSTACK
        Route::group(['prefix' => 'paystack', 'as' => 'paystack.'], function () {
            Route::get('pay', [PaystackController::class, 'index'])->name('pay');
            Route::post('payment', [PaystackController::class, 'redirectToGateway'])->name('payment');
            Route::get('callback', [PaystackController::class, 'handleGatewayCallback'])->name('callback');
        });
    });
}

Route::get('payment-success', [PaymentController::class, 'success'])->name('payment-success');
Route::get('payment-fail', [PaymentController::class, 'fail'])->name('payment-fail');

/** Admin */
Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Web\Admin', 'middleware' => ['admin', 'mpc:system_management']], function () {
    Route::group(['prefix' => 'configuration', 'as' => 'configuration.'], function () {
        Route::get('payment-get', [PaymentConfigController::class, 'payment_config_get'])->name('payment-get');
        Route::put('payment-set', [PaymentConfigController::class, 'payment_config_set'])->name('payment-set');

        Route::group(['prefix' => 'offline-payment', 'as'=>'offline-payment.'], function () {
            Route::any('list', 'OfflinePaymentController@method_list')->name('list');
            Route::get('create', 'OfflinePaymentController@method_create')->name('create');
            Route::post('store', 'OfflinePaymentController@method_store')->name('store');
            Route::get('edit/{id}', 'OfflinePaymentController@method_edit')->name('edit');
            Route::put('update', 'OfflinePaymentController@method_update')->name('update');
            Route::delete('delete/{id}', 'OfflinePaymentController@method_destroy')->name('delete');
            Route::any('status-update/{id}', 'OfflinePaymentController@method_status_update')->name('status-update');
        });
    });

    Route::group(['prefix' => 'bonus', 'as' => 'bonus.'], function () {
        Route::any('list', [BonusController::class, 'list'])->name('list');
        Route::get('create', [BonusController::class, 'create'])->name('create');
        Route::post('store', [BonusController::class, 'store'])->name('store');
        Route::get('edit/{id}', [BonusController::class, 'edit'])->name('edit');
        Route::put('update/{id}', [BonusController::class, 'update'])->name('update');
        Route::delete('delete/{id}', [BonusController::class, 'destroy'])->name('delete');
        Route::any('status-update/{id}', [BonusController::class, 'status_update'])->name('status-update');
        Route::any('download', [BonusController::class, 'download'])->name('download');
    });
});
