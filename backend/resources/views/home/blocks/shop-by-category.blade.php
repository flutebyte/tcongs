@props(['block', 'categories'])

@if($categories->isNotEmpty())
  <section class="bg-greysoft py-10 md:py-[60px]">
    <div class="mx-auto w-full max-w-wrapper px-3 md:px-4">
      <x-section-header :title="$block->title ?: 'Shop by Category'" :subtitle="$block->subtitle" :cta-label="$block->cta_label" :cta-url="$block->cta_url" />
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
@endif
