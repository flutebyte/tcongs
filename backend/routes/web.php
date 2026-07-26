<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/{category:slug}', [CategoryController::class, 'show'])->name('categories.show');
Route::get('/collections', [CollectionController::class, 'index'])->name('collections.index');
Route::get('/collections/{collection:slug}', [CollectionController::class, 'show'])->name('collections.show');
Route::get('/products/{product:slug}', [ProductController::class, 'show'])->name('products.show');
Route::get('/search', [SearchController::class, 'index'])->name('search');

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
// Registered before the {product:slug} wildcard below — otherwise a POST to
// /cart/coupon would match /cart/{product:slug} first, with "coupon" bound
// as the slug (and 404 on lookup) instead of ever reaching applyCoupon().
Route::post('/cart/coupon', [CartController::class, 'applyCoupon'])->name('cart.coupon.apply')->middleware('throttle:20,1');
Route::delete('/cart/coupon', [CartController::class, 'removeCoupon'])->name('cart.coupon.remove')->middleware('throttle:20,1');
Route::post('/cart/{product:slug}', [CartController::class, 'store'])->name('cart.store');
Route::patch('/cart/items/{cartItem}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/items/{cartItem}', [CartController::class, 'destroy'])->name('cart.destroy');

Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
// Rate-limited: order_number is a guessable-ish slug and the only "credential"
// for viewing a guest order — this isn't full auth, just enumeration friction.
Route::get('/checkout/confirmation/{order:order_number}', [CheckoutController::class, 'confirmation'])
    ->name('checkout.confirmation')
    ->middleware('throttle:20,1');
