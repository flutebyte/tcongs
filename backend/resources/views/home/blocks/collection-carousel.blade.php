@props(['block'])

@php $collections = $block->items->pluck('itemable')->filter(); @endphp

@if($collections->isNotEmpty())
  <section class="py-10 md:py-[60px] bg-pinksoft">
    <div class="mx-auto w-full max-w-wrapper px-6 md:px-14 lg:px-20 xl:px-[7vw]">
      <div class="mb-5 text-center md:mb-[30px]">
        <h2 class="relative pb-3 after:absolute after:bottom-0 after:left-1/2 after:h-0.5 after:w-[46px] after:-translate-x-1/2 after:bg-accent text-[18px] md:text-[21px] xl:text-[24px] font-medium uppercase tracking-[0.5px] text-heading">{{ $block->title }}</h2>
      </div>
      <div class="mx-auto grid max-w-[1040px] grid-cols-2 gap-3 px-3 sm:gap-4 sm:px-5 md:max-w-[1100px] md:grid-cols-4 md:gap-5 md:px-8 lg:gap-6 lg:px-10">
        @foreach($collections as $collection)
          <a class="block" href="{{ route('collections.show', $collection) }}">
            <span class="block overflow-hidden rounded-[8%]">
              @if($collection->hasMedia('image'))
                <img class="h-auto w-full object-contain transition-transform duration-700 hover:scale-[1.03]"
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
