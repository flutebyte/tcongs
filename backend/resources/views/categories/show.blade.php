@extends('layouts.app')

@section('meta_title', ($category->seoMeta?->title ?? $category->name).' | '.($siteSettings['site_name'] ?? 'Estele'))
@section('meta_description', $category->seoMeta?->description ?? $category->description)
@if($category->seoMeta?->og_image || $category->hasMedia('image'))
  @section('og_image', $category->seoMeta?->og_image ?? $category->getFirstMediaUrl('image', 'tile'))
@endif

@section('content')

  {{-- No wide banner section here: Category images are portrait product/tile photography
       (used for the Shop by Category carousel), not wide banner art like Collections have.
       Force-cropping a portrait image into a 16:5 banner just showed a blank middle slice. --}}

  <nav class="mx-auto w-full max-w-wrapper px-3 md:px-4 flex flex-wrap items-center gap-1.5 py-4 text-[13px] text-muted" aria-label="Breadcrumb">
    <x-breadcrumb :items="[['label' => $category->name]]" />
  </nav>

  <div class="mx-auto w-full max-w-wrapper px-3 md:px-4">
    <header class="pb-6 pt-2 text-center md:pb-[30px]">
      <h1 class="text-[20px] uppercase tracking-[0.5px] md:text-[26px] mb-2.5">{{ $category->name }}</h1>
      @if($category->description)
        <p class="mx-auto max-w-[70ch] text-[13.5px] text-muted">{{ $category->description }}</p>
      @endif
    </header>
  </div>

  <div class="mx-auto w-full max-w-wrapper px-3 md:px-4 pb-10 md:pb-[60px]">

    <div class="mb-5 flex flex-wrap items-center gap-3.5 border-b border-line pb-4.5">
      <p class="w-full text-[13px] text-muted md:mr-auto md:w-auto">{{ $products->total() }} {{ \Illuminate\Support\Str::plural('product', $products->total()) }}</p>
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
      <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 md:gap-5 xl:grid-cols-4">
        @foreach($products as $product)
          <x-product-card :product="$product" />
        @endforeach
      </div>

      <div class="mt-8">
        {{ $products->links() }}
      </div>
    @endif
  </div>

@endsection
