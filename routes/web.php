<?php

use App\Http\Controllers\FrontendController;
use App\Http\Controllers\HomeController;
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

// Route::get('/', [LandingController::class, 'home'])->name('home');
Route::get('page/about-us', [LandingController::class, 'about_us'])->name('page.about-us');
Route::get('page/privacy-policy', [LandingController::class, 'privacy_policy'])->name('page.privacy-policy');
Route::get('page/terms-and-conditions', [LandingController::class, 'terms_and_conditions'])->name('page.terms-and-conditions');
Route::get('page/contact-us', [LandingController::class, 'contact_us'])->name('page.contact-us');
Route::get('page/cancellation-policy', [LandingController::class, 'cancellation_policy'])->name('page.cancellation-policy');
Route::get('page/refund-policy', [LandingController::class, 'refund_policy'])->name('page.refund-policy');
Route::get('login', [FrontendController::class, 'login'])->name('login');
Route::post('login', [FrontendController::class, 'customer_login'])->name('customer-login');
Route::get('register', [FrontendController::class, 'register'])->name('register');
Route::post('register', [FrontendController::class, 'customer_register'])->name('customer-register');
Route::get('/', [FrontendController::class, 'index'])->name('home');
Route::get('categories', [FrontendController::class, 'categories'])->name('categories');
Route::get('services', [FrontendController::class, 'services'])->name('services');
Route::get('category-services/{id}', [FrontendController::class, 'category_services'])->name('categoryservices');
Route::get('service-detail/{id}', [FrontendController::class, 'service_detail'])->name('service-detail');
Route::get('cart', [FrontendController::class, 'cart'])->name('cart');
Route::get('checkout', [HomeController::class, 'checkout'])->name('customer.checkout');
Route::post('book-now', [HomeController::class, 'place_request'])->name('book-now');
Route::post('cart', [FrontendController::class, 'addToCart'])->name('cart-store');
Route::get('cart-remove/{id}', [FrontendController::class, 'cart_remove'])->name('cart-remove');
Route::get('profile', [HomeController::class, 'profile'])->name('profile');
Route::post('logout', [HomeController::class, 'customer_logout'])->name('customer-logout');
Route::post('updateprofile', [HomeController::class, 'updateprofile'])->name('updateprofile');
Route::post('updatepassword', [HomeController::class, 'updatepassword'])->name('updatepassword');
Route::get('blogs', [HomeController::class, 'blogs'])->name('blogs');
Route::get('blog-detail/{id}', [HomeController::class, 'blog_detail'])->name('blog-detail');

Route::fallback(function () {
    return redirect('admin/auth/login');
});
