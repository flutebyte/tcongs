<?php

namespace App\Providers;

use App\Models\Banner;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\Category;
use App\Models\CmsPage;
use App\Models\Collection;
use App\Models\Coupon;
use App\Models\Faq;
use App\Models\FaqCategory;
use App\Models\HomepageBlock;
use App\Models\HomepageBlockItem;
use App\Models\Offer;
use App\Models\Product;
use App\Models\Setting;
use App\Observers\BannerObserver;
use App\Observers\BlogCategoryObserver;
use App\Observers\BlogObserver;
use App\Observers\CategoryObserver;
use App\Observers\CmsPageObserver;
use App\Observers\CollectionObserver;
use App\Observers\CouponObserver;
use App\Observers\FaqCategoryObserver;
use App\Observers\FaqObserver;
use App\Observers\HomepageBlockItemObserver;
use App\Observers\HomepageBlockObserver;
use App\Observers\OfferObserver;
use App\Observers\ProductObserver;
use App\Observers\SettingObserver;
use App\View\Composers\SiteDataComposer;
use BezhanSalleh\FilamentShield\Facades\FilamentShield;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        FilamentShield::enforcePolicies();

        View::composer('layouts.app', SiteDataComposer::class);

        // Keep SiteDataComposer's 1-hour nav/settings cache from ever serving stale
        // content after an admin save — see the "cache invalidation" Definition of Done gap.
        Category::observe(CategoryObserver::class);
        Collection::observe(CollectionObserver::class);
        Setting::observe(SettingObserver::class);
        Product::observe(ProductObserver::class);
        Banner::observe(BannerObserver::class);
        HomepageBlock::observe(HomepageBlockObserver::class);
        HomepageBlockItem::observe(HomepageBlockItemObserver::class);
        Offer::observe(OfferObserver::class);
        Coupon::observe(CouponObserver::class);
        BlogCategory::observe(BlogCategoryObserver::class);
        Blog::observe(BlogObserver::class);
        CmsPage::observe(CmsPageObserver::class);
        FaqCategory::observe(FaqCategoryObserver::class);
        Faq::observe(FaqObserver::class);
    }
}
