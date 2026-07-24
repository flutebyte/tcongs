@props(['block'])

@php $item = $block->items->first(); @endphp
@php $collection = $item?->itemable; @endphp

@if($collection)
  <section class="my-8 md:my-[60px]">
    <div class="mb-5 text-center md:mb-[30px]">
      <h2 class="relative pb-3 after:absolute after:bottom-0 after:left-1/2 after:h-0.5 after:w-[46px] after:-translate-x-1/2 after:bg-accent text-[18px] md:text-[21px] xl:text-[24px] font-medium uppercase tracking-[0.5px] text-heading">{{ $block->title }}</h2>
    </div>
    <a class="block aspect-[1400/560] w-full overflow-hidden bg-placeholder" href="{{ route('collections.show', $collection) }}">
      @if($item->hasMedia('image'))
        <img class="h-full w-full object-contain" src="{{ $item->getFirstMediaUrl('image', 'banner') }}" alt="{{ $block->title }}" loading="lazy" width="1400" height="560">
      @endif
    </a>
  </section>
@endif
