<?php

use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\Auth\LoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Front\ProductController;


// Fallback
Route::fallback(function () {
    abort(404); 
});

// Front Routes
Route::controller(ProductController::class)->name('front.products.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/products/{product}', 'show')->name('show');
});

// Auth Routes
Route::controller(LoginController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login')->middleware('guest');
    Route::post('/login', 'login')->name('login.submit')->middleware('guest');
    Route::get('/logout', 'logout')->name('logout')->middleware('auth');
});

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::resource('products', AdminProductController::class)->except('show');
});