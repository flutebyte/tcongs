<?php

namespace App\View\Composers;

use App\Models\Cart;
use App\Models\Category;
use App\Models\Collection as CollectionModel;
use App\Models\Coupon;
use App\Models\Offer;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class SiteDataComposer
{
    public function compose(View $view): void
    {
        $cart = Cart::with('coupon')->where('session_id', session()->getId())->first();
        $cartItems = $cart ? $cart->items()->with(['product.media', 'variant'])->get() : collect();

        $cartDiscount = 0.0;
        if ($cart?->coupon) {
            $result = $cart->coupon->isValidFor($cart);
            $cartDiscount = $result['valid'] ? $result['discount'] : 0.0;
        }

        $view->with('cartCount', (int) $cartItems->sum('quantity'));
        $view->with('cartItems', $cartItems);
        $view->with('cartSubtotal', $cartItems->sum(fn ($item) => $item->unitPrice() * $item->quantity));
        $view->with('cartDiscount', $cartDiscount);
        $view->with('cartCouponCode', $cart?->coupon?->code);

        // Laravel's database/file cache stores refuse to unserialize objects by default
        // (config('cache.serializable_classes') === false) — cache plain arrays, not
        // Eloquent models/Collections, to avoid silently getting __PHP_Incomplete_Class back.
        $view->with('navCategories', Cache::remember(
            'site.nav_categories',
            3600,
            fn () => Category::orderBy('sort_order')->get(['id', 'name', 'slug'])->toArray()
        ));

        $view->with('navCollections', Cache::remember(
            'site.nav_collections',
            3600,
            fn () => CollectionModel::active()->ordered()->get(['id', 'name', 'slug'])->toArray()
        ));

        $view->with('siteSettings', Cache::remember(
            'site.settings',
            3600,
            fn () => Setting::pluck('value', 'key')->toArray()
        ));

        $view->with('activeOffers', Cache::remember(
            'site.active_offers',
            3600,
            fn () => Offer::active()->ordered()->get(['text'])->pluck('text')->toArray()
        ));

        // Shorter TTL than the other site-wide caches above — usage limits and
        // expiry make a coupon's "listable" status change more often than nav
        // categories or settings do.
        $view->with('publicCoupons', Cache::remember(
            'site.public_coupons',
            300,
            fn () => Coupon::listable()
                ->orderByDesc('created_at')
                ->get()
                ->map(fn (Coupon $coupon) => ['code' => $coupon->code, 'summary' => $coupon->summary()])
                ->toArray()
        ));
    }
}
