<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;

Route::get('/', [ProductController::class, 'index'])->name('home');

Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout']);

Route::resource('products', ProductController::class);
Route::get('/products/search', [ProductController::class, 'search'])->name('products.search');

Route::resource('cart', CartController::class);
Route::get('/cart/count', [CartController::class, 'count'])->name('cart.count');
Route::delete('/cart/clear', [CartController::class, 'clearCart'])->name('cart.clear');

Route::resource('orders', OrderController::class);