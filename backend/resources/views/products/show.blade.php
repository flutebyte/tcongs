@extends('layouts.app')

@section('meta_title', ($product->seoMeta?->title ?? $product->title).' | '.($siteSettings['site_name'] ?? 'Estele'))
@section('meta_description', $product->seoMeta?->description ?? $product->description)
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
  @endphp

  <nav class="mx-auto w-full max-w-wrapper px-3 md:px-4 flex flex-wrap items-center gap-1.5 py-4 text-[13px] text-muted" aria-label="Breadcrumb">
    <x-breadcrumb :items="array_filter([
      $primaryCategory ? ['label' => $primaryCategory->name, 'url' => route('categories.show', $primaryCategory)] : null,
      ['label' => $product->title],
    ])" />
  </nav>

  <div class="mx-auto w-full max-w-wrapper px-3 md:px-4 grid grid-cols-1 gap-8 pb-10 md:grid-cols-2 md:gap-[46px] md:pb-[60px]">

    <div>
      <div class="aspect-square overflow-hidden rounded bg-placeholder">
        @if($product->hasMedia('gallery'))
          <img class="h-full w-full object-cover" id="pdp-main-img" src="{{ $product->getFirstMediaUrl('gallery', 'detail') }}" alt="{{ $product->title }}" width="1000" height="1000" fetchpriority="high">
        @endif
      </div>
      @if($galleryImages->count() > 1)
        <div class="mt-2.5 grid grid-cols-4 gap-2.5">
          @foreach($galleryImages as $index => $media)
            <button class="aspect-square overflow-hidden rounded border bg-placeholder transition-colors {{ $index === 0 ? 'border-accent' : 'border-transparent hover:border-accent' }}" type="button"
                    data-gallery-thumb data-full="{{ $media->getUrl('detail') }}">
              <img class="h-full w-full object-cover" src="{{ $media->getUrl('card') }}" alt="{{ $product->title }} view {{ $index + 1 }}" loading="lazy" width="160" height="160">
            </button>
          @endforeach
        </div>
      @endif
    </div>

    <div>
      <h1 class="mb-2.5 text-[20px] md:text-[28px]">{{ $product->title }}</h1>

      <div class="mb-1 flex flex-wrap items-center gap-2.5 text-[21px]">
        <span class="font-medium text-price">₹{{ number_format($product->price, 0) }}</span>
        @if($product->compare_at_price)
          <span class="text-muted line-through">₹{{ number_format($product->compare_at_price, 0) }}</span>
          <span class="inline-block bg-salebadge px-2.5 py-1 text-[11px] font-medium uppercase leading-none text-white">{{ $discountPercent }}% OFF</span>
        @endif
      </div>
      <p class="mb-5 text-[12px] text-muted">Inclusive of all taxes</p>

      <form action="{{ route('cart.store', $product) }}" method="post">
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
      </form>

      <ul class="mt-6 space-y-2">
        <li class="relative pl-5 text-[13px] text-muted before:absolute before:left-0 before:top-[7px] before:h-2 before:w-2 before:rounded-full before:bg-accent">Anti-tarnish coating</li>
        <li class="relative pl-5 text-[13px] text-muted before:absolute before:left-0 before:top-[7px] before:h-2 before:w-2 before:rounded-full before:bg-accent">1 year manufacturing warranty</li>
        <li class="relative pl-5 text-[13px] text-muted before:absolute before:left-0 before:top-[7px] before:h-2 before:w-2 before:rounded-full before:bg-accent">Free shipping on prepaid orders</li>
        <li class="relative pl-5 text-[13px] text-muted before:absolute before:left-0 before:top-[7px] before:h-2 before:w-2 before:rounded-full before:bg-accent">Easy 7-day returns</li>
      </ul>

      <div class="mt-7">
        @if($product->description)
          <details class="marker-pm" open>
            <summary class="flex items-center justify-between border-t border-line py-4 text-[13px] font-medium uppercase tracking-[0.4px] text-heading">Description</summary>
            <div class="pb-4.5 text-[13.5px] leading-[1.85] text-muted">
              <p>{{ $product->description }}</p>
            </div>
          </details>
        @endif
        <details class="marker-pm border-b border-line">
          <summary class="flex items-center justify-between border-t border-line py-4 text-[13px] font-medium uppercase tracking-[0.4px] text-heading">Shipping &amp; Returns</summary>
          <div class="pb-4.5 text-[13.5px] leading-[1.85] text-muted">
            <p>Free shipping on all prepaid orders across India. Orders are dispatched within
               24&ndash;48 hours. Returns and exchanges accepted within 7 days of delivery,
               provided the product is unused and in original packaging.</p>
          </div>
        </details>
      </div>
    </div>
  </div>

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

@endsection
