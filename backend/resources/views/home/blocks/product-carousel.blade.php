@props(['block'])

@php $products = $block->items->pluck('itemable')->filter(); @endphp

@if($products->isNotEmpty())
  <section class="mx-auto w-full max-w-wrapper py-10 md:py-[60px]">
    <div class="w-full max-w-wrapper">
      @if($block->title || $block->subtitle || $block->cta_label)
        <div class="mb-5 text-center md:mb-[30px]">
          @if($block->title)
            <h2 class="text-[18px] md:text-[21px] xl:text-[24px] font-medium uppercase tracking-[0.5px] text-heading">{{ $block->title }}</h2>
          @endif
          @if($block->subtitle)
            <p class="mt-1 text-[13px] text-muted">{{ $block->subtitle }}</p>
          @endif
          @if($block->cta_label)
            <a class="mt-2 inline-flex items-center gap-1.5 border-b border-current pb-0.5 text-[13px] font-medium uppercase tracking-[0.5px]" href="{{ $block->cta_url ?? '#' }}">{{ $block->cta_label }}</a>
          @endif
        </div>
      @endif
      <div class="relative" data-carousel>
        <button class="absolute top-1/2 z-[3] hidden h-10 w-10 -translate-y-1/2 place-items-center rounded-full bg-white shadow-md transition-colors hover:bg-accent hover:text-white disabled:pointer-events-none disabled:opacity-0 md:grid -left-2 xl:-left-[18px]" type="button" data-carousel-prev aria-label="Previous">
          <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg>
        </button>
        <div class="carousel-track gap-3 md:gap-5" data-carousel-track>
          @foreach($products as $product)
            <div class="flex-[0_0_calc((100%-3*12px)/2)] md:flex-[0_0_calc((100%-3*20px)/4)]">
              <x-product-card :product="$product" />
            </div>
          @endforeach
        </div>
        <button class="absolute top-1/2 z-[3] hidden h-10 w-10 -translate-y-1/2 place-items-center rounded-full bg-white shadow-md transition-colors hover:bg-accent hover:text-white disabled:pointer-events-none disabled:opacity-0 md:grid -right-2 xl:-right-[18px]" type="button" data-carousel-next aria-label="Next">
          <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
        </button>
      </div>
    </div>
  </section>
@endif
