@extends('layouts.app')

@if($banners->isNotEmpty() && $banners->first()->hasMedia('image'))
  @section('og_image', $banners->first()->getFirstMediaUrl('image', 'desktop'))
@endif

@section('content')

  {{-- Organization JSON-LD (SEO checklist item) — name, logo, social links, all admin-editable via Settings. --}}
  <script type="application/ld+json">
    {!! json_encode(array_filter([
      '@context' => 'https://schema.org',
      '@type' => 'Organization',
      'name' => $siteSettings['site_name'] ?? 'Estele',
      'url' => route('home'),
      'logo' => $siteSettings['site_logo_url'] ?? null,
      'sameAs' => array_values(array_filter([
        $siteSettings['social_instagram'] ?? null,
        $siteSettings['social_facebook'] ?? null,
        $siteSettings['social_twitter'] ?? null,
        $siteSettings['social_youtube'] ?? null,
      ])),
      'contactPoint' => ($siteSettings['contact_phone'] ?? null) ? array_filter([
        '@type' => 'ContactPoint',
        'telephone' => $siteSettings['contact_phone'],
        'email' => $siteSettings['contact_email'] ?? null,
        'contactType' => 'customer service',
      ]) : null,
    ]), JSON_UNESCAPED_SLASHES) !!}
  </script>

  @if($banners->isNotEmpty())
    <div class="mx-auto w-full px-8 md:px-9">
      {{--
        Banner height reduced 40% from the original aspect ratios (mobile
        750/1000 -> 750/600, desktop 1800/700 -> 1800/420). Uses an inline
        style rather than a new Tailwind aspect-[...] utility class: this
        backend has no live Tailwind build of its own — public/theme/app.css
        is a static copy of the separate root project's compiled CSS (see
        tcongs-d2c-spec memory), so any brand-new arbitrary-value class
        written only in a backend Blade file compiles to nothing and the
        element would silently lose its aspect ratio entirely. The old class
        list also had a latent bug: two unprefixed aspect-[...] utilities of
        equal specificity with no breakpoint on the mobile one, so which
        ratio actually won depended on Tailwind's generation order rather
        than intent — sidestepped here since inline style always wins CSS
        cascade order regardless of stylesheet compile order.
      --}}
      <style>@media (min-width: 768px) { .hero-banner-shortened { aspect-ratio: 1800 / 420 !important; } }</style>
      <section class="hero-fade hero-banner-shortened relative overflow-hidden rounded-[1.25rem] mb-2 w-full" style="aspect-ratio: 750 / 600" aria-label="Featured collections" data-carousel data-autoplay="5000" data-fade>
        @foreach($banners as $index => $banner)
          <div class="hero-slide {{ $index === 0 ? 'is-active' : '' }}" data-carousel-slide>
            <a href="{{ $banner->link_url ?? '#' }}" aria-label="{{ $banner->title }}">
              @if($banner->hasMedia('image'))
                <picture>
                  <source media="(max-width: 767px)" srcset="{{ $banner->getFirstMediaUrl('image', 'mobile') }}">
                  <img class="h-full w-full object-cover" src="{{ $banner->getFirstMediaUrl('image', 'desktop') }}" alt="{{ $banner->title }}" loading="{{ $index === 0 ? 'eager' : 'lazy' }}" fetchpriority="{{ $index === 0 ? 'high' : 'auto' }}">
                </picture>
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

  @foreach($homepageBlocks as $block)
    @includeIf('home.blocks.'.str_replace('_', '-', $block->type), ['block' => $block, 'categories' => $categories])
  @endforeach

@endsection
