<?php

use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\Auth\LoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Front\ProductController;

Route::get('/', [ProductController::class, 'index'])->name('front.products.show');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('front.products.show');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

// Laravel Best Practices
// Route::middleware(['auth'])->group(function () {
//     Route::resource('/admin/products', AdminProductController::class);
// });

Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::resource('products', AdminProductController::class);
});

