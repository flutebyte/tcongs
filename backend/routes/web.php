<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CmsPageController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

// Admin-editable (Settings key 'robots_txt', see SettingSeeder) — falls back to a
// sane default if never configured. There's deliberately no public/robots.txt
// static file (it would be served directly by the app server, bypassing this
// route entirely) — see the note in public/.gitignore-style removal below.
Route::get('/robots.txt', function () {
    $settings = \Illuminate\Support\Facades\Cache::remember(
        'site.settings',
        3600,
        fn () => \App\Models\Setting::pluck('value', 'key')->toArray()
    );
    $content = $settings['robots_txt'] ?? null;

    if (! $content) {
        $content = "User-agent: *\nAllow: /\nDisallow: /cart\nDisallow: /checkout\nDisallow: /account\nDisallow: /login\nDisallow: /register\nDisallow: /payment\nDisallow: /search\n\nSitemap: ".url('/sitemap.xml');
    }

    return response($content, 200)->header('Content-Type', 'text/plain');
})->name('robots');

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/{category:slug}', [CategoryController::class, 'show'])->name('categories.show');
Route::get('/collections', [CollectionController::class, 'index'])->name('collections.index');
Route::get('/collections/{collection:slug}', [CollectionController::class, 'show'])->name('collections.show');
Route::get('/products/{product:slug}', [ProductController::class, 'show'])->name('products.show');
Route::post('/products/{product:slug}/reviews', [ReviewController::class, 'store'])
    ->name('products.reviews.store')
    ->middleware('throttle:5,60');
// Phase 6 security audit: spec §9 explicitly names search alongside login/
// OTP/checkout for rate limiting — generous enough for real typing/browsing,
// still caps scraping/abuse.
Route::get('/search', [SearchController::class, 'index'])->name('search')->middleware('throttle:60,1');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.attempt')->middleware('throttle:10,1');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.attempt')->middleware('throttle:10,1');
});
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::get('/account', [AccountController::class, 'index'])->name('account.index');
    Route::patch('/account/profile', [AccountController::class, 'updateProfile'])->name('account.profile');
    Route::patch('/account/password', [AccountController::class, 'updatePassword'])->name('account.password');
});

Route::post('/newsletter/subscribe', [NewsletterController::class, 'store'])
    ->name('newsletter.subscribe')
    ->middleware('throttle:10,60');

Route::get('/blogs', [BlogController::class, 'index'])->name('blogs.index');
Route::get('/blogs/{blog:slug}', [BlogController::class, 'show'])->name('blogs.show');
Route::get('/faq', [FaqController::class, 'index'])->name('faq.index');
Route::get('/pages/{cmsPage:slug}', [CmsPageController::class, 'show'])->name('pages.show');

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
// Phase 6 security audit: this was the one order-placing endpoint with no
// rate limit at all — every other checkout-adjacent route already has one.
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store')->middleware('throttle:10,1');
// Looked up server-side (not straight from the browser) because
// api.postalpincode.in doesn't send CORS headers, so a direct client fetch
// is blocked; this also keeps the lookup rate-limited from one place.
Route::get('/checkout/pincode/{postalCode}', [CheckoutController::class, 'pincodeLookup'])
    ->name('checkout.pincode-lookup')
    ->middleware('throttle:30,1')
    ->where('postalCode', '[0-9]{6}');
// Rate-limited: order_number is a guessable-ish slug and the only "credential"
// for viewing a guest order — this isn't full auth, just enumeration friction.
Route::get('/checkout/confirmation/{order:order_number}', [CheckoutController::class, 'confirmation'])
    ->name('checkout.confirmation')
    ->middleware('throttle:20,1');

Route::get('/payment/{order:order_number}', [PaymentController::class, 'show'])
    ->name('payment.show')
    ->middleware('throttle:20,1');
Route::post('/payment/{order:order_number}/callback', [PaymentController::class, 'callback'])
    ->name('payment.callback')
    ->middleware('throttle:20,1');
// No CSRF/throttle here on purpose — Razorpay's servers post this directly
// (see bootstrap/app.php's validateCsrfTokens except-list) and the signature
// check inside the handler is what authenticates it, not a session-bound token.
Route::post('/webhooks/razorpay', [PaymentController::class, 'webhook'])->name('webhooks.razorpay');

// Redirect Manager (spec §5/§6): see bootstrap/app.php's withExceptions for
// where a 404 actually gets checked against the redirects table — a plain
// Route::fallback() here would only catch structurally-unmatched paths, not
// a stale slug on a route like /products/{product:slug} (that matches the
// URI shape fine and throws ModelNotFoundException instead).
