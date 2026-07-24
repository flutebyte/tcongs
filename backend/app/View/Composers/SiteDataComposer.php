<?php

namespace App\View\Composers;

use App\Models\Cart;
use App\Models\Category;
use App\Models\Collection as CollectionModel;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class SiteDataComposer
{
    public function compose(View $view): void
    {
        $cart = Cart::where('session_id', session()->getId())->first();
        $cartItems = $cart ? $cart->items()->with(['product.media', 'variant'])->get() : collect();

        $view->with('cartCount', (int) $cartItems->sum('quantity'));
        $view->with('cartItems', $cartItems);
        $view->with('cartSubtotal', $cartItems->sum(fn ($item) => $item->unitPrice() * $item->quantity));

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
    }
}
