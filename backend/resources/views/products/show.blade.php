@extends('layouts.app')

@section('meta_title', ($product->seoMeta?->title ?? $product->title).' | '.($siteSettings['site_name'] ?? 'Estele'))
@section('meta_description', $product->seoMeta?->description ?: ($product->description ?: $product->title))
@section('og_type', 'product')
@if($product->seoMeta?->og_image || $product->hasMedia('gallery'))
  @section('og_image', $product->seoMeta?->og_image ?? $product->getFirstMediaUrl('gallery', 'detail'))
@endif

@section('content')

  @php
    $primaryCategory = $product->categories->first();
    $discountPercent = $product->compare_at_price
      ? (int) round((($product->compare_at_price - $product->price) / $product->compare_at_price) * 100)
      : null;
    $galleryImages = $product->getMedia('gallery');
    $mainMedia = $galleryImages->first();
    $ratingAverage = $product->reviewsAverageRating();
    $ratingCount = $product->reviewsCount();
    $breadcrumbItems = array_filter([
      $primaryCategory ? ['label' => $primaryCategory->name, 'url' => route('categories.show', $primaryCategory)] : null,
      ['label' => $product->title],
    ]);
  @endphp

  <script type="application/ld+json">
    {!! json_encode(array_filter([
      '@context' => 'https://schema.org',
      '@type' => 'Product',
      'name' => $product->title,
      'image' => $galleryImages->map(fn ($media) => $media->getUrl('detail'))->all(),
      'description' => $product->description,
      'sku' => $product->sku,
      'brand' => ['@type' => 'Brand', 'name' => $siteSettings['site_name'] ?? 'Estele'],
      'offers' => [
        '@type' => 'Offer',
        'url' => route('products.show', $product),
        'priceCurrency' => 'INR',
        'price' => (string) $product->price,
        'availability' => $product->stock_quantity > 0 ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
      ],
      'aggregateRating' => $ratingCount > 0 ? [
        '@type' => 'AggregateRating',
        'ratingValue' => $ratingAverage,
        'reviewCount' => $ratingCount,
      ] : null,
      'review' => $reviews->isNotEmpty() ? $reviews->map(fn ($review) => [
        '@type' => 'Review',
        'author' => ['@type' => 'Person', 'name' => $review->customer_name],
        'datePublished' => $review->created_at->toIso8601String(),
        'reviewBody' => $review->body,
        'reviewRating' => [
          '@type' => 'Rating',
          'ratingValue' => $review->rating,
          'bestRating' => 5,
          'worstRating' => 1,
        ],
      ])->all() : null,
    ]), JSON_UNESCAPED_SLASHES | JSON_HEX_TAG) !!}
  </script>

  <x-breadcrumb-schema :items="$breadcrumbItems" />

  <nav class="mx-auto w-full max-w-wrapper px-3 md:px-4 flex flex-wrap items-center gap-1.5 py-4 text-[13px] text-muted" aria-label="Breadcrumb">
    <x-breadcrumb :items="$breadcrumbItems" />
  </nav>

  {{--
    Gallery layout: real Estele product pages run a vertical thumbnail strip
    beside the main image on desktop, not a grid below it (verified live via
    browser). Reproduced with plain scoped CSS rather than new Tailwind
    utility classes — see the note on padding above for why: this backend's
    public/theme/app.css is a static copy of a separate project's compiled
    CSS, so a fresh utility class written only here would render as nothing.
    Individual thumbnail buttons keep their original Tailwind classes
    (aspect-square/border-accent/etc.) since those are already proven
    compiled in this exact file.

    Same reasoning covers everything else new below (.pdp-image-wrap /
    .pdp-zoom-* / .pdp-lightbox* / .pdp-title-row / .pdp-icon-*): plain scoped
    CSS/JS, not new Tailwind classes. These reproduce three things confirmed
    missing here vs. the real Estele PDP (checked live via browser) — a
    hover-to-zoom lens with a magnified side panel, an expand icon that opens
    the current image full-screen, and wishlist/share icons beside the title.
  --}}
  <style>
    .pdp-gallery { display: flex; flex-direction: column; gap: 10px; }
    .pdp-thumbs { display: flex; flex-direction: row; gap: 10px; overflow-x: auto; order: 2; }
    .pdp-thumbs .pdp-thumb { flex: 0 0 72px; width: 72px; }
    .pdp-image-wrap { position: relative; order: 1; }
    @media (min-width: 768px) {
      .pdp-gallery { flex-direction: row; align-items: flex-start; }
      .pdp-thumbs { flex-direction: column; overflow-x: visible; overflow-y: auto; order: 1; max-height: 600px; width: 84px; flex: 0 0 84px; }
      .pdp-thumbs .pdp-thumb { width: 100%; flex: 0 0 auto; }
      .pdp-image-wrap { order: 2; flex: 1 1 auto; min-width: 0; }
    }

    .pdp-icon-btn {
      display: flex; align-items: center; justify-content: center;
      height: 34px; width: 34px; padding: 0; border-radius: 999px;
      border: 1px solid var(--color-line); background: transparent;
      color: var(--color-heading); cursor: pointer;
      transition: color .15s ease, border-color .15s ease;
    }
    .pdp-icon-btn:hover, .pdp-icon-btn.is-active { border-color: var(--color-accent); color: var(--color-accent); }
    .pdp-icon-btn svg { height: 16px; width: 16px; }

    .pdp-expand-btn {
      position: absolute; top: 10px; right: 10px; z-index: 5;
      background: var(--color-white); border: 0;
      box-shadow: 0 1px 4px rgba(0, 0, 0, .18);
    }

    /* Hover-to-zoom lens + magnified side panel, matching estele.co's PDP gallery. */
    .pdp-zoom-lens {
      position: absolute; display: none; pointer-events: none; z-index: 4;
      border: 1px solid rgba(20, 20, 20, .45); background: rgba(255, 255, 255, .35);
    }
    .pdp-zoom-pane {
      position: absolute; display: none; top: 0; left: 100%; margin-left: 16px;
      width: 100%; height: 100%; z-index: 20; border-radius: 4px;
      background-color: var(--color-placeholder); background-repeat: no-repeat;
      box-shadow: 0 8px 30px rgba(0, 0, 0, .16);
    }
    @media (min-width: 1024px) {
      .pdp-main { cursor: zoom-in; }
    }

    .pdp-lightbox {
      position: fixed; inset: 0; z-index: 100; display: flex;
      align-items: center; justify-content: center;
      background: rgba(0, 0, 0, .85); padding: 24px;
    }
    .pdp-lightbox img { max-width: 100%; max-height: 100%; object-fit: contain; }
    .pdp-lightbox-close {
      position: absolute; top: 16px; right: 20px; z-index: 1;
      background: none; border: 0; color: var(--color-white);
      font-size: 32px; line-height: 1; cursor: pointer;
    }

    .pdp-title-row { display: flex; align-items: flex-start; justify-content: space-between; gap: 12px; }
    .pdp-title-row h1 { margin: 0; }
    .pdp-icon-group { display: flex; align-items: center; gap: 8px; flex-shrink: 0; }
    .pdp-share-tip {
      position: absolute; bottom: calc(100% + 8px); left: 50%; transform: translateX(-50%);
      white-space: nowrap; background: var(--color-heading); color: var(--color-white);
      font-size: 11px; padding: 4px 8px; border-radius: 4px;
    }
  </style>

  {{--
    Wrapped in <article> (not just a plain <div>) so the site-wide wishlist heart
    click-handler in app.js — which walks up to the nearest article/li to find an
    <img> for its localStorage key — resolves to this product's own image here too,
    same as it does for product cards on listing pages.
  --}}
  <article class="mx-auto w-full max-w-wrapper px-3 md:px-4 grid grid-cols-1 gap-8 pb-10 md:grid-cols-2 md:gap-[46px] md:pb-[60px]">

    <div class="pdp-gallery">
      @if($galleryImages->count() > 1)
        <div class="pdp-thumbs">
          @foreach($galleryImages as $index => $media)
            <button class="pdp-thumb aspect-square overflow-hidden rounded border bg-placeholder transition-colors {{ $index === 0 ? 'border-accent' : 'border-transparent hover:border-accent' }}" type="button"
                    data-gallery-thumb data-full="{{ $media->getUrl('detail') }}">
              <img class="h-full w-full object-cover" src="{{ $media->getUrl('card') }}" alt="{{ $product->title }} view {{ $index + 1 }}" loading="lazy" width="160" height="160">
            </button>
          @endforeach
        </div>
      @endif
      <div class="pdp-image-wrap">
        {{-- Image area reduced ~20% via inline padding — see product-card.blade.php for why this isn't a Tailwind p-[...] class. --}}
        <div class="pdp-main aspect-square overflow-hidden rounded bg-placeholder" style="padding: 5.3%" id="pdp-zoom-frame">
          @if($mainMedia)
            <img class="h-full w-full object-cover" id="pdp-main-img"
                 src="{{ $mainMedia->getUrl('detail') }}"
                 srcset="{{ $mainMedia->getUrl('mobile') }} 768w, {{ $mainMedia->getUrl('tablet') }} 1024w, {{ $mainMedia->getUrl('detail') }} 1600w"
                 sizes="(max-width: 768px) 100vw, 50vw"
                 alt="{{ $product->title }}" width="1000" height="1000" fetchpriority="high">
          @endif
          <div class="pdp-zoom-lens" id="pdp-zoom-lens"></div>
        </div>
        @if($mainMedia)
          <button class="pdp-icon-btn pdp-expand-btn" type="button" id="pdp-expand-btn" aria-label="View full image">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M9 3H3v6M15 3h6v6M9 21H3v-6M15 21h6v-6"/></svg>
          </button>
          <div class="pdp-zoom-pane" id="pdp-zoom-pane"></div>
        @endif
      </div>
    </div>

    <div>
      <div class="pdp-title-row mb-1.5">
        <h1 class="text-[20px] md:text-[28px]">{{ $product->title }}</h1>
        <div class="pdp-icon-group">
          <button class="pdp-icon-btn" type="button" aria-label="Add to wishlist">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1-1.1a5.5 5.5 0 0 0-7.8 7.8l1.1 1.1L12 21.2l7.7-7.7 1.1-1.1a5.5 5.5 0 0 0 0-7.8z"/></svg>
          </button>
          <button class="pdp-icon-btn" type="button" id="pdp-share-btn" aria-label="Share">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><circle cx="18" cy="5" r="2.4"/><circle cx="6" cy="12" r="2.4"/><circle cx="18" cy="19" r="2.4"/><path d="M8.1 10.7l7.8-4.4M8.1 13.3l7.8 4.4"/></svg>
          </button>
        </div>
      </div>
      @if($product->sku)
        <p class="mb-2.5 text-[12px] text-muted">SKU: {{ $product->sku }}</p>
      @endif

      @if($ratingCount > 0)
        <a class="mb-2.5 inline-flex items-center gap-2" href="#reviews">
          <x-review-stars :rating="$ratingAverage" :count="$ratingCount" />
        </a>
      @endif

      <div class="mb-1 flex flex-wrap items-center gap-2.5 text-[21px]">
        <span class="font-medium text-price">₹{{ number_format($product->price, 0) }}</span>
        @if($product->compare_at_price)
          <span class="text-muted line-through">₹{{ number_format($product->compare_at_price, 0) }}</span>
          <span class="inline-block bg-salebadge px-2.5 py-1 text-[11px] font-medium uppercase leading-none text-white">{{ $discountPercent }}% OFF</span>
        @endif
      </div>
      <p class="mb-4 text-[12px] text-muted">Inclusive of all taxes</p>

      {{-- Trust badge row, matching real Estele's icon strip placed right after the price. --}}
      <div class="mb-4 flex flex-wrap items-center gap-4 text-[12px] text-muted">
        <span class="flex items-center gap-1.5">
          <svg class="h-4 w-4 shrink-0 text-accent" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M12 3l7 3v6c0 4.5-3 7.5-7 9-4-1.5-7-4.5-7-9V6z"/><path d="M9 12l2 2 4-4"/></svg>
          100% Anti-Tarnish
        </span>
        <span class="flex items-center gap-1.5">
          <svg class="h-4 w-4 shrink-0 text-accent" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M3 12a9 9 0 1 0 3-6.7"/><path d="M3 4v5h5"/></svg>
          7-Day Return &amp; Exchange
        </span>
        <span class="flex items-center gap-1.5">
          <svg class="h-4 w-4 shrink-0 text-accent" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M3 7h11v8H3z"/><path d="M14 10h4l3 3v2h-7z"/><circle cx="7" cy="18" r="1.6"/><circle cx="17.5" cy="18" r="1.6"/></svg>
          Free Shipping Available
        </span>
      </div>

      @include('partials.offers-banner')

      <form class="mt-4" action="{{ route('cart.store', $product) }}" method="post" data-cart-form data-checkout-url="{{ route('checkout.index') }}">
        @csrf

        @if($product->variants->isNotEmpty())
          <div class="mb-5">
            <span class="mb-2 block text-[13px] font-medium uppercase tracking-[0.4px]">Options</span>
            <div class="flex flex-wrap gap-2">
              @foreach($product->variants as $index => $variant)
                <label class="cursor-pointer border px-4 py-2 text-[13px] font-medium transition-colors has-[:checked]:border-heading has-[:checked]:text-heading border-line-strong text-muted hover:border-heading hover:text-heading {{ $variant->stock_quantity <= 0 ? 'opacity-40' : '' }}">
                  <input class="sr-only" type="radio" name="product_variant_id" value="{{ $variant->id }}" {{ $index === 0 ? 'checked' : '' }} {{ $variant->stock_quantity <= 0 ? 'disabled' : '' }}>
                  {{ collect($variant->attributes ?? [])->map(fn($v, $k) => "{$k}: {$v}")->implode(', ') ?: $variant->sku }}
                </label>
              @endforeach
            </div>
          </div>
        @endif

        <div class="mb-2.5 flex gap-2.5">
          <div class="inline-flex items-center border border-line-strong">
            <input class="h-11 w-[64px] border-0 text-center text-[14px]" type="number" name="quantity" value="1" min="1" aria-label="Quantity">
          </div>
          <button class="inline-flex items-center justify-center gap-2 border border-black bg-black px-8 py-[13px] text-[13px] font-medium uppercase tracking-[0.5px] text-white transition-colors hover:border-accent hover:bg-accent flex-1 py-3.5" type="submit"
                  {{ $product->stock_quantity <= 0 ? 'disabled' : '' }}>
            {{ $product->stock_quantity > 0 ? 'Add to Cart' : 'Out of Stock' }}
          </button>
        </div>
        <button class="inline-flex items-center justify-center gap-2 border border-black bg-transparent px-8 py-[13px] text-[13px] font-medium uppercase tracking-[0.5px] text-black transition-colors hover:bg-black hover:text-white w-full py-3.5" type="submit" name="buy_now" value="1"
                {{ $product->stock_quantity <= 0 ? 'disabled' : '' }}>
          Buy It Now
        </button>
      </form>

      <div class="mt-7">
        @if($product->description)
          <details class="marker-pm" open>
            <summary class="flex items-center justify-between border-t border-line py-4 text-[13px] font-medium uppercase tracking-[0.4px] text-heading">Description</summary>
            <div class="pb-4.5 text-[13.5px] leading-[1.85] text-muted">
              <p>{{ $product->description }}</p>
            </div>
          </details>
        @endif
        <details class="marker-pm">
          <summary class="flex items-center justify-between border-t border-line py-4 text-[13px] font-medium uppercase tracking-[0.4px] text-heading">Return &amp; Exchange Policy</summary>
          <div class="pb-4.5 text-[13.5px] leading-[1.85] text-muted">
            <p>Free shipping on all prepaid orders across India. Orders are dispatched within
               24&ndash;48 hours. Returns and exchanges accepted within 7 days of delivery,
               provided the product is unused and in original packaging.</p>
          </div>
        </details>
        <details class="marker-pm">
          <summary class="flex items-center justify-between border-t border-line py-4 text-[13px] font-medium uppercase tracking-[0.4px] text-heading">Manufacturing Details</summary>
          <div class="pb-4.5 text-[13.5px] leading-[1.85] text-muted">
            <p>Adorn yourself with the allure of anti-tarnish jewelry, exuding beauty and durability.
               @if($product->sku) SKU: {{ $product->sku }}. @endif
               Every piece is quality-checked before dispatch.</p>
          </div>
        </details>
        <details class="marker-pm border-b border-line">
          <summary class="flex items-center justify-between border-t border-line py-4 text-[13px] font-medium uppercase tracking-[0.4px] text-heading">Care &amp; Maintenance</summary>
          <ul class="pb-4.5 space-y-2">
            <li class="relative pl-5 text-[13px] text-muted before:absolute before:left-0 before:top-[7px] before:h-2 before:w-2 before:rounded-full before:bg-accent">Keep jewellery away from water &amp; humidity</li>
            <li class="relative pl-5 text-[13px] text-muted before:absolute before:left-0 before:top-[7px] before:h-2 before:w-2 before:rounded-full before:bg-accent">Remove jewellery before sleeping or physical activities</li>
            <li class="relative pl-5 text-[13px] text-muted before:absolute before:left-0 before:top-[7px] before:h-2 before:w-2 before:rounded-full before:bg-accent">Remove jewellery before bathing, showering or swimming</li>
            <li class="relative pl-5 text-[13px] text-muted before:absolute before:left-0 before:top-[7px] before:h-2 before:w-2 before:rounded-full before:bg-accent">Avoid direct contact with perfume, body lotions or other chemicals</li>
            <li class="relative pl-5 text-[13px] text-muted before:absolute before:left-0 before:top-[7px] before:h-2 before:w-2 before:rounded-full before:bg-accent">Clean the piece occasionally and wipe with a soft cloth</li>
            <li class="relative pl-5 text-[13px] text-muted before:absolute before:left-0 before:top-[7px] before:h-2 before:w-2 before:rounded-full before:bg-accent">Store separately in an air-tight jewellery box</li>
          </ul>
        </details>
      </div>
    </div>
  </article>

  @if($mainMedia)
    <div class="pdp-lightbox" id="pdp-lightbox" hidden>
      <button class="pdp-lightbox-close" type="button" data-lightbox-close aria-label="Close">&times;</button>
      <img id="pdp-lightbox-img" src="" alt="">
    </div>
  @endif

  @if($relatedProducts->isNotEmpty())
    <section class="py-10 md:py-[60px]">
      <div class="mx-auto w-full max-w-wrapper px-3 md:px-4">
        <x-section-header title="You May Also Like" />
        <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 md:gap-5 xl:grid-cols-4">
          @foreach($relatedProducts as $related)
            <x-product-card :product="$related" />
          @endforeach
        </div>
      </div>
    </section>
  @endif

  {{-- "Our Promise to You" trust strip, matching real Estele's PDP (customer-count claim dropped — that figure is Estele's own, not this store's). --}}
  <section class="border-t border-line py-10 md:py-[60px]">
    <div class="mx-auto w-full max-w-wrapper px-3 md:px-4">
      <x-section-header title="Our Promise to You" />
      <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 md:gap-5">
        @foreach(['One Year Warranty', 'Unparalleled Quality', 'Brilliant Designs', 'Made in India', 'Free Shipping'] as $promise)
          <div class="rounded-lg border border-line p-4 text-center">
            <p class="text-[11px] font-medium uppercase tracking-[0.3px] text-heading">{{ $promise }}</p>
          </div>
        @endforeach
      </div>
    </div>
  </section>

  <section class="border-t border-line py-10 md:py-[60px]" id="reviews">
    <div class="mx-auto w-full max-w-[760px] px-3 md:px-4">
      <x-section-header
        title="Customer Reviews"
        :subtitle="$ratingCount > 0 ? number_format($ratingAverage, 1).' out of 5, based on '.$ratingCount.' review'.($ratingCount === 1 ? '' : 's') : 'No reviews yet — be the first to write one'"
      />

      @if(session('success'))
        <p class="mb-6 rounded-lg border border-line bg-pinksoft px-4 py-3 text-center text-[13px] text-heading">{{ session('success') }}</p>
      @endif

      @if($reviews->isNotEmpty())
        <ul class="mb-8 space-y-5">
          @foreach($reviews as $review)
            <li class="border-b border-line pb-5">
              <div class="mb-1.5 flex flex-wrap items-center gap-2">
                <x-review-stars :rating="$review->rating" />
                @if($review->is_verified_purchase)
                  <span class="text-[11px] font-medium uppercase tracking-[0.3px] text-accent">Verified Purchase</span>
                @endif
              </div>
              @if($review->title)
                <p class="mb-1 text-[14px] font-medium text-heading">{{ $review->title }}</p>
              @endif
              <p class="mb-2 text-[13.5px] leading-[1.7] text-muted">{{ $review->body }}</p>
              @if($review->hasMedia('photos'))
                <div class="mb-2 flex flex-wrap gap-2">
                  @foreach($review->getMedia('photos') as $photo)
                    <img class="h-16 w-16 rounded object-cover" src="{{ $photo->getUrl('thumb') }}" alt="Photo submitted with {{ $review->customer_name }}'s review" loading="lazy" width="64" height="64">
                  @endforeach
                </div>
              @endif
              <p class="text-[12px] text-muted">{{ $review->customer_name }} &middot; {{ $review->created_at->format('d M Y') }}</p>
            </li>
          @endforeach
        </ul>

        <div class="mb-8">
          {{ $reviews->links() }}
        </div>
      @endif

      <details class="marker-pm" {{ $errors->any() ? 'open' : '' }}>
        <summary class="cursor-pointer border-t border-line py-4 text-[13px] font-medium uppercase tracking-[0.4px] text-heading">Write a Review</summary>
        <form class="pb-4.5" action="{{ route('products.reviews.store', $product) }}" method="post" enctype="multipart/form-data">
          @csrf
          <input class="hidden" type="text" name="website" tabindex="-1" autocomplete="off">

          <div class="mb-3.5 grid grid-cols-1 gap-3 sm:grid-cols-2">
            <div>
              <label class="mb-1.5 block text-[13px] font-medium text-heading" for="customer_name">Name</label>
              <input class="w-full border border-line-strong bg-white px-4 py-3 text-[14px] outline-none transition-colors placeholder:text-muted focus:border-heading" id="customer_name" name="customer_name" type="text" value="{{ old('customer_name') }}" required>
              @error('customer_name') <p class="mt-1 text-[12px] text-salebadge">{{ $message }}</p> @enderror
            </div>
            <div>
              <label class="mb-1.5 block text-[13px] font-medium text-heading" for="customer_email">Email</label>
              <input class="w-full border border-line-strong bg-white px-4 py-3 text-[14px] outline-none transition-colors placeholder:text-muted focus:border-heading" id="customer_email" name="customer_email" type="email" value="{{ old('customer_email') }}" required>
              @error('customer_email') <p class="mt-1 text-[12px] text-salebadge">{{ $message }}</p> @enderror
            </div>
          </div>

          <div class="mb-3.5">
            <label class="mb-1.5 block text-[13px] font-medium text-heading" for="rating">Rating</label>
            <select class="w-full border border-line-strong bg-white px-4 py-3 text-[14px] outline-none transition-colors focus:border-heading" id="rating" name="rating" required>
              <option value="">Select a rating</option>
              @for($i = 5; $i >= 1; $i--)
                <option value="{{ $i }}" {{ old('rating') == $i ? 'selected' : '' }}>{{ $i }} star{{ $i === 1 ? '' : 's' }}</option>
              @endfor
            </select>
            @error('rating') <p class="mt-1 text-[12px] text-salebadge">{{ $message }}</p> @enderror
          </div>

          <div class="mb-3.5">
            <label class="mb-1.5 block text-[13px] font-medium text-heading" for="title">Title (optional)</label>
            <input class="w-full border border-line-strong bg-white px-4 py-3 text-[14px] outline-none transition-colors placeholder:text-muted focus:border-heading" id="title" name="title" type="text" value="{{ old('title') }}">
            @error('title') <p class="mt-1 text-[12px] text-salebadge">{{ $message }}</p> @enderror
          </div>

          <div class="mb-3.5">
            <label class="mb-1.5 block text-[13px] font-medium text-heading" for="body">Review</label>
            <textarea class="w-full border border-line-strong bg-white px-4 py-3 text-[14px] outline-none transition-colors placeholder:text-muted focus:border-heading" id="body" name="body" rows="4" required>{{ old('body') }}</textarea>
            @error('body') <p class="mt-1 text-[12px] text-salebadge">{{ $message }}</p> @enderror
          </div>

          <div class="mb-5">
            <label class="mb-1.5 block text-[13px] font-medium text-heading" for="photos">Photos (optional, up to 3)</label>
            <input class="w-full border border-line-strong bg-white px-4 py-3 text-[13px] outline-none transition-colors focus:border-heading" id="photos" name="photos[]" type="file" accept="image/*" multiple>
            @error('photos') <p class="mt-1 text-[12px] text-salebadge">{{ $message }}</p> @enderror
            @error('photos.*') <p class="mt-1 text-[12px] text-salebadge">{{ $message }}</p> @enderror
          </div>

          <button class="inline-flex items-center justify-center gap-2 border border-black bg-black px-8 py-[13px] text-[13px] font-medium uppercase tracking-[0.5px] text-white transition-colors hover:border-accent hover:bg-accent" type="submit">
            Submit Review
          </button>
        </form>
      </details>
    </div>
  </section>

@endsection

@push('scripts')
  <script>
    (function () {
      // Hover-to-zoom: a lens follows the cursor over the main image, and a
      // magnified crop renders in a side panel — same interaction as estele.co's
      // PDP gallery. Desktop-only (min-width 1024px + hover-capable pointer);
      // the CSS media query above is a second guard in case JS resolves this
      // before layout settles.
      var frame = document.getElementById('pdp-zoom-frame');
      var mainImg = document.getElementById('pdp-main-img');
      var lens = document.getElementById('pdp-zoom-lens');
      var pane = document.getElementById('pdp-zoom-pane');
      var canZoom = frame && mainImg && lens && pane;
      var hoverCapable = window.matchMedia && window.matchMedia('(hover: hover) and (pointer: fine)').matches;
      var ZOOM = 2.4;

      function syncPaneImage() {
        pane.style.backgroundImage = 'url("' + (mainImg.currentSrc || mainImg.src) + '")';
      }

      if (canZoom) {
        syncPaneImage();
        mainImg.addEventListener('load', syncPaneImage);

        frame.addEventListener('mousemove', function (e) {
          if (!hoverCapable || window.innerWidth < 1024) return;
          var rect = frame.getBoundingClientRect();
          var x = e.clientX - rect.left;
          var y = e.clientY - rect.top;
          if (x < 0 || y < 0 || x > rect.width || y > rect.height) return;

          var lensW = rect.width / ZOOM;
          var lensH = rect.height / ZOOM;
          var lx = Math.min(Math.max(x - lensW / 2, 0), rect.width - lensW);
          var ly = Math.min(Math.max(y - lensH / 2, 0), rect.height - lensH);

          lens.style.width = lensW + 'px';
          lens.style.height = lensH + 'px';
          lens.style.left = lx + 'px';
          lens.style.top = ly + 'px';
          lens.style.display = 'block';
          pane.style.display = 'block';

          var bgX = rect.width - lensW > 0 ? (lx / (rect.width - lensW)) * 100 : 0;
          var bgY = rect.height - lensH > 0 ? (ly / (rect.height - lensH)) * 100 : 0;
          pane.style.backgroundSize = (ZOOM * 100) + '%';
          pane.style.backgroundPosition = bgX + '% ' + bgY + '%';
        });

        frame.addEventListener('mouseleave', function () {
          lens.style.display = 'none';
          pane.style.display = 'none';
        });
      }

      // Expand icon -> full-screen lightbox of the current main image.
      var expandBtn = document.getElementById('pdp-expand-btn');
      var lightbox = document.getElementById('pdp-lightbox');
      var lightboxImg = document.getElementById('pdp-lightbox-img');
      var lightboxClose = lightbox && lightbox.querySelector('[data-lightbox-close]');

      if (expandBtn && lightbox && lightboxImg && mainImg) {
        function openLightbox() {
          lightboxImg.src = mainImg.currentSrc || mainImg.src;
          lightboxImg.alt = mainImg.alt;
          lightbox.hidden = false;
          document.body.style.overflow = 'hidden';
        }
        function closeLightbox() {
          lightbox.hidden = true;
          document.body.style.overflow = '';
        }
        expandBtn.addEventListener('click', openLightbox);
        if (lightboxClose) lightboxClose.addEventListener('click', closeLightbox);
        lightbox.addEventListener('click', function (e) {
          if (e.target === lightbox) closeLightbox();
        });
        document.addEventListener('keydown', function (e) {
          if (e.key === 'Escape' && !lightbox.hidden) closeLightbox();
        });
      }

      // Share icon -> native share sheet where available, else copy the link.
      var shareBtn = document.getElementById('pdp-share-btn');
      if (shareBtn) {
        shareBtn.addEventListener('click', function () {
          var shareData = { title: document.title, url: window.location.href };
          if (navigator.share) {
            navigator.share(shareData).catch(function () {});
            return;
          }
          if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(shareData.url).then(function () {
              var tip = document.createElement('span');
              tip.textContent = 'Link copied';
              tip.className = 'pdp-share-tip';
              shareBtn.style.position = 'relative';
              shareBtn.appendChild(tip);
              setTimeout(function () { tip.remove(); }, 1600);
            }).catch(function () {});
          }
        });
      }
    })();
  </script>
@endpush
