<?php

use App\Http\Controllers\LandingController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', [LandingController::class, 'home'])->name('home');
Route::get('page/about-us', [LandingController::class, 'about_us'])->name('page.about-us');
Route::get('page/privacy-policy', [LandingController::class, 'privacy_policy'])->name('page.privacy-policy');
Route::get('page/terms-and-conditions', [LandingController::class, 'terms_and_conditions'])->name('page.terms-and-conditions');
Route::get('page/contact-us', [LandingController::class, 'contact_us'])->name('page.contact-us');
Route::get('page/cancellation-policy', [LandingController::class, 'cancellation_policy'])->name('page.cancellation-policy');
Route::get('page/refund-policy', [LandingController::class, 'refund_policy'])->name('page.refund-policy');

Route::fallback(function () {
    return redirect('admin/auth/login');
});
