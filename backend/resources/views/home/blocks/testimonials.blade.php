@props(['block'])

<section class="py-10 md:py-[60px] bg-pinksoft">
  <div class="mx-auto w-full max-w-wrapper px-3 md:px-4">
    @if($block->title)
      <div class="mb-5 text-center md:mb-[30px]">
        <h2 class="text-[18px] md:text-[21px] xl:text-[24px] font-medium uppercase tracking-[0.5px] text-heading">{{ $block->title }}</h2>
      </div>
    @endif
    <div class="relative" data-carousel>
      <button class="absolute top-1/2 z-[3] hidden h-10 w-10 -translate-y-1/2 place-items-center rounded-full bg-white shadow-md transition-colors hover:bg-accent hover:text-white disabled:pointer-events-none disabled:opacity-0 md:grid -left-2 xl:-left-[18px]" type="button" data-carousel-prev aria-label="Previous">
        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg>
      </button>
      <div class="carousel-track gap-3.5" data-carousel-track>
        @foreach($block->items as $item)
          <div class="flex-[0_0_88%] md:flex-[0_0_calc((100%-2*14px)/3)] xl:flex-[0_0_calc((100%-4*14px)/5)]">
            <blockquote class="flex h-full flex-col rounded-md bg-cream p-4">
              <div class="mb-2.5 text-[13px] tracking-wider">
                @for($i = 0; $i < 5; $i++)
                  <span class="{{ $i < ($item->rating ?? 5) ? 'text-star' : 'text-line-strong' }}">&#9733;</span>
                @endfor
              </div>
              <p class="mb-3.5 flex-1 text-[12.5px] leading-relaxed text-ink">{{ $item->body }}</p>
              <cite class="text-[12px] not-italic text-muted">- {{ $item->title }}</cite>
            </blockquote>
          </div>
        @endforeach
      </div>
      <button class="absolute top-1/2 z-[3] hidden h-10 w-10 -translate-y-1/2 place-items-center rounded-full bg-white shadow-md transition-colors hover:bg-accent hover:text-white disabled:pointer-events-none disabled:opacity-0 md:grid -right-2 xl:-right-[18px]" type="button" data-carousel-next aria-label="Next">
        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
      </button>
    </div>
  </div>
</section>
