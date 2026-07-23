@extends('layouts.app')

@section('meta_title', 'Search'.($query !== '' ? " — {$query}" : '').' | '.($siteSettings['site_name'] ?? 'Estele'))

@section('content')

  <nav class="mx-auto w-full max-w-wrapper px-3 md:px-4 flex flex-wrap items-center gap-1.5 py-4 text-[13px] text-muted" aria-label="Breadcrumb">
    <x-breadcrumb :items="[['label' => 'Search']]" />
  </nav>

  <div class="mx-auto w-full max-w-wrapper px-3 md:px-4 pb-10 md:pb-[60px]">
    <h1 class="text-[20px] uppercase tracking-[0.5px] md:text-[26px] mb-5">Search</h1>

    <form class="mb-3.5 flex max-w-[620px] gap-2.5" role="search">
      <label class="sr-only-custom" for="q">Search</label>
      <input class="w-full border border-line-strong bg-white px-4 py-3 text-[14px] outline-none transition-colors placeholder:text-muted focus:border-heading flex-1" id="q" type="search" name="q" value="{{ $query }}" placeholder="Search for jewellery...">
      <button class="inline-flex items-center justify-center gap-2 border border-black bg-black px-8 py-[13px] text-[13px] font-medium uppercase tracking-[0.5px] text-white transition-colors hover:border-accent hover:bg-accent" type="submit">Search</button>
    </form>

    @if($query !== '')
      <p class="mb-6 text-[13px] text-muted">{{ $products->total() }} {{ \Illuminate\Support\Str::plural('result', $products->total()) }} for &ldquo;{{ $query }}&rdquo;</p>
    @endif

    @if($products->isEmpty())
      <p class="py-16 text-center text-[13px] text-muted">
        @if($query === '')
          Enter a search term above to find products.
        @else
          No products matched &ldquo;{{ $query }}&rdquo;.
        @endif
      </p>
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
