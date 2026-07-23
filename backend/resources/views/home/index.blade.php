@extends('layouts.app')

@if($banners->isNotEmpty() && $banners->first()->hasMedia('image'))
  @section('og_image', $banners->first()->getFirstMediaUrl('image', 'desktop'))
@endif

@section('content')

  @if($banners->isNotEmpty())
    <div class="mx-auto w-full px-8 md:px-9">
      <section class="hero-fade relative overflow-hidden rounded-[1.25rem] mb-2 aspect-[1800/700] w-full md:aspect-[1800/700] aspect-[750/1000]" aria-label="Featured collections" data-carousel data-autoplay="5000" data-fade>
        @foreach($banners as $index => $banner)
          <div class="hero-slide {{ $index === 0 ? 'is-active' : '' }}" data-carousel-slide>
            <a href="{{ $banner->link_url ?? '#' }}" aria-label="{{ $banner->title }}">
              @if($banner->hasMedia('image'))
                <img class="h-full w-full object-cover" src="{{ $banner->getFirstMediaUrl('image', 'desktop') }}" alt="{{ $banner->title }}" loading="{{ $index === 0 ? 'eager' : 'lazy' }}" fetchpriority="{{ $index === 0 ? 'high' : 'auto' }}">
              @endif
            </a>
          </div>
        @endforeach
        <button class="absolute left-3 top-1/2 z-[3] hidden h-10 w-10 -translate-y-1/2 place-items-center rounded-full bg-white/90 shadow-md md:grid" type="button" data-hero-prev aria-label="Previous slide">
          <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg>
        </button>
        <button class="absolute right-3 top-1/2 z-[3] hidden h-10 w-10 -translate-y-1/2 place-items-center rounded-full bg-white/90 shadow-md md:grid" type="button" data-hero-next aria-label="Next slide">
          <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
        </button>
      </section>
      <div class="flex justify-center gap-2 bg-white py-2.5" data-hero-dots>
        @foreach($banners as $index => $banner)
          <button class="h-2 w-2 rounded-full transition-colors {{ $index === 0 ? 'bg-heading' : 'bg-line-strong' }}" type="button" data-hero-dot="{{ $index }}" aria-label="Go to slide {{ $index + 1 }}"></button>
        @endforeach
      </div>
    </div>
  @endif

  <section class="bg-greysoft py-10 md:py-[60px]">
    <div class="mx-auto w-full max-w-wrapper px-3 md:px-4">
      <x-section-header title="Shop by Category" />
      <div class="relative" data-carousel>
        <button class="absolute top-1/2 z-[3] hidden h-10 w-10 -translate-y-1/2 place-items-center rounded-full bg-white shadow-md transition-colors hover:bg-accent hover:text-white disabled:pointer-events-none disabled:opacity-0 md:grid -left-2 xl:-left-[18px]" type="button" data-carousel-prev aria-label="Previous">
          <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg>
        </button>
        <div class="carousel-track gap-2 md:gap-3" data-carousel-track>
          @foreach($categories as $category)
            <div class="flex-[0_0_calc((100%-8px)/2)] md:flex-[0_0_calc((100%-2*12px)/3)] xl:flex-[0_0_calc((100%-7*12px)/8)]">
              <x-collection-tile :category="$category" />
            </div>
          @endforeach
        </div>
        <button class="absolute top-1/2 z-[3] hidden h-10 w-10 -translate-y-1/2 place-items-center rounded-full bg-white shadow-md transition-colors hover:bg-accent hover:text-white disabled:pointer-events-none disabled:opacity-0 md:grid -right-2 xl:-right-[18px]" type="button" data-carousel-next aria-label="Next">
          <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
        </button>
      </div>
    </div>
  </section>

  @foreach($homepageBlocks as $block)
    @includeIf('home.blocks.'.str_replace('_', '-', $block->type), ['block' => $block])
  @endforeach

@endsection
