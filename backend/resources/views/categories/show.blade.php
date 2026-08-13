@extends('layouts.app')

@section('meta_title', ($category->seoMeta?->title ?? $category->name).' | '.($siteSettings['site_name'] ?? 'Estele'))
@section('meta_description', $category->seoMeta?->description ?: ($category->description ?: $category->name))
@if($category->seoMeta?->og_image || $category->hasMedia('image'))
  @section('og_image', $category->seoMeta?->og_image ?? $category->getFirstMediaUrl('image', 'tile'))
@endif

@section('content')

  {{-- No wide banner section here: Category images are portrait product/tile photography
       (used for the Shop by Category carousel), not wide banner art like Collections have.
       Force-cropping a portrait image into a 16:5 banner just showed a blank middle slice. --}}

  <x-breadcrumb-schema :items="[['label' => $category->name]]" />

  {{-- Page title/subtitle removed by design ask — breadcrumb is the only page
       identifier now. The product count that used to live in the subtitle is
       still shown, just folded into the "Showing X–Y of N" line below. --}}
  <nav class="mx-auto w-full max-w-wrapper px-3 md:px-4 flex flex-wrap items-center gap-1.5 py-4 text-[13px] text-muted" aria-label="Breadcrumb">
    <x-breadcrumb :items="[['label' => $category->name]]" />
  </nav>

  <div class="mx-auto w-full max-w-wrapper px-3 md:px-4 pb-10 md:pb-[60px]">

    <x-filter-panel
      :action="route('categories.show', $category)"
      :sort="$sort"
      :min-price="$minPrice"
      :max-price="$maxPrice"
      :in-stock="$inStock"
      :categories="$category->children"
      :selected-categories="$subcategorySlugs"
    />

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
      <p class="py-16 text-center text-[13px] text-muted">No products in this category yet — check back soon.</p>
    @else
      {{-- Three real tiers now (mobile/tablet/desktop), not just two — see
           collections/show.blade.php's comment on why this is a scoped class
           + media query, not sm:grid-cols-4 (no live Tailwind build here, so
           a brand-new arbitrary utility would compile to nothing). Previously
           this jumped straight from 2-up to 4-up at 640px, so every tablet
           width got the same cramped 4-up desktop layout; 768–1023px now
           gets its own 3-up tier. --}}
      <style>
        @media (min-width: 640px) { .product-grid-4up { grid-template-columns: repeat(3, minmax(0, 1fr)); } }
        @media (min-width: 1024px) { .product-grid-4up { grid-template-columns: repeat(4, minmax(0, 1fr)); } }
      </style>
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
