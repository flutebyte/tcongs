@extends('layouts.app')

@section('meta_title', ($collection->seoMeta?->title ?? $collection->name).' | '.($siteSettings['site_name'] ?? 'Estele'))
@section('meta_description', $collection->seoMeta?->description ?: ($collection->description ?: $collection->name))
@if($collection->seoMeta?->og_image || $collection->hasMedia('image'))
  @section('og_image', $collection->seoMeta?->og_image ?? $collection->getFirstMediaUrl('image', 'banner'))
@endif

@section('content')

  <x-breadcrumb-schema :items="[['label' => $collection->name]]" />

  <nav class="mx-auto w-full max-w-wrapper px-3 md:px-4 flex flex-wrap items-center gap-1.5 py-4 text-[13px] text-muted" aria-label="Breadcrumb">
    <x-breadcrumb :items="[['label' => $collection->name]]" />
  </nav>

  <div class="mx-auto w-full max-w-wrapper px-3 md:px-4">
    <header class="pb-6 pt-2 text-center md:pb-[30px]">
      <h1 class="text-[20px] uppercase tracking-[0.5px] md:text-[26px] mb-2.5">{{ $collection->name }}</h1>
      @if($collection->description)
        <p class="mx-auto max-w-[70ch] text-[13.5px] text-muted">{{ $collection->description }}</p>
      @endif
    </header>
  </div>

  <div class="mx-auto w-full max-w-wrapper px-3 md:px-4 pb-10 md:pb-[60px]">

    <div class="mb-5 flex flex-wrap items-center gap-3.5 border-b border-line pb-4.5">
      <p class="w-full text-[13px] text-muted md:mr-auto md:w-auto">
        @if($products->total() > 0)
          Showing {{ $products->firstItem() }}&ndash;{{ $products->lastItem() }} of {{ $products->total() }}
        @else
          No products
        @endif
      </p>
      <label class="ml-auto">
        <span class="sr-only-custom">Sort by</span>
        <select class="border border-line-strong bg-white px-3 py-2 text-[13px] outline-none transition-colors focus:border-heading" onchange="window.location.href=this.value">
          <option value="{{ request()->fullUrlWithQuery(['sort' => 'featured']) }}" @selected($sort === 'featured')>Featured</option>
          <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_asc']) }}" @selected($sort === 'price_asc')>Price: Low to High</option>
          <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_desc']) }}" @selected($sort === 'price_desc')>Price: High to Low</option>
          <option value="{{ request()->fullUrlWithQuery(['sort' => 'newest']) }}" @selected($sort === 'newest')>Newest</option>
        </select>
      </label>
    </div>

    @if($products->isEmpty())
      <p class="py-16 text-center text-[13px] text-muted">No products in this collection yet — check back soon.</p>
    @else
      {{-- 4-up from tablet width up (was 3 sm/md, 4 only at xl) — sm:grid-cols-4
           isn't a class used anywhere in the root static site's scanned content,
           so it wouldn't compile in this backend's static CSS copy (see the
           "no live Tailwind build" note elsewhere in this file family); scoped
           class + media query instead, same workaround as .hero-banner-shortened. --}}
      <style>@media (min-width: 640px) { .product-grid-4up { grid-template-columns: repeat(4, minmax(0, 1fr)); } }</style>
      <div class="product-grid-4up grid grid-cols-2 gap-3 md:gap-5">
        @foreach($products as $product)
          <x-product-card :product="$product" />
        @endforeach
      </div>

      <x-pagination-links :paginator="$products" />
      <div class="mt-8">
        {{ $products->links() }}
      </div>
    @endif
  </div>

@endsection
