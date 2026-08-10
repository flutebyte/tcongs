@props(['block'])

@php $collections = $block->items->pluck('itemable')->filter(); @endphp

@if($collections->isNotEmpty())
  <section class="py-10 md:py-[60px] bg-pinksoft">
    <div class="mx-auto w-full max-w-wrapper px-3 md:px-4">
      <div class="mb-5 text-center md:mb-[30px]">
        <h2 class="relative pb-3 after:absolute after:bottom-0 after:left-1/2 after:h-0.5 after:w-[46px] after:-translate-x-1/2 after:bg-accent text-[18px] md:text-[21px] xl:text-[24px] font-medium uppercase tracking-[0.5px] text-heading">{{ $block->title }}</h2>
        @if($block->subtitle)
          <p class="mt-1 text-[13px] text-muted">{{ $block->subtitle }}</p>
        @endif
        @if($block->cta_label)
          <a class="mt-2 inline-flex items-center gap-1.5 border-b border-current pb-0.5 text-[13px] font-medium uppercase tracking-[0.5px]" href="{{ $block->cta_url ?? '#' }}">{{ $block->cta_label }}</a>
        @endif
      </div>
      <div class="mx-auto grid max-w-[1040px] grid-cols-2 gap-3 sm:gap-4 md:max-w-[1100px] md:grid-cols-4 md:gap-5 lg:gap-6">
        @foreach($collections as $collection)
          <a class="block" href="{{ route('collections.show', $collection) }}">
            {{-- aspect-ratio reserves the tile's box before the image loads —
                 without it this was the one image grid in the codebase with
                 no CLS protection (every sibling block already has this).
                 Kept object-contain/h-full (not object-cover): Collection's
                 'tile' media conversion only constrains width, not aspect
                 ratio, so forcing a crop here could cut off real uploads —
                 object-contain letterboxes instead, same as before this fix. --}}
            <span class="block overflow-hidden rounded-[8%]" style="aspect-ratio: 2/3;">
              @if($collection->hasMedia('image'))
                <img class="h-full w-full object-contain transition-transform duration-700 hover:scale-[1.03]"
                     src="{{ $collection->getFirstMediaUrl('image', 'tile') }}"
                     alt="{{ $collection->name }}" loading="lazy">
              @endif
            </span>
          </a>
        @endforeach
      </div>
    </div>
  </section>
@endif
