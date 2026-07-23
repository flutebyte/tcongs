@props(['block'])

<section class="py-10 md:py-[60px]">
  <div class="mx-auto w-full max-w-wrapper px-3 md:px-4">
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
      @foreach($block->items as $item)
        <div class="p-2.5 text-center">
          @if($item->hasMedia('image'))
            <img class="mx-auto mb-3 h-[46px] w-[46px] md:h-14 md:w-14" src="{{ $item->getFirstMediaUrl('image') }}" alt="" loading="lazy" width="56" height="56">
          @endif
          <h3 class="mb-1.5 text-[15px] font-medium">{{ $item->title }}</h3>
          @if($item->body)
            <p class="text-[12px] tracking-[0.3px] text-muted">{{ $item->body }}</p>
          @endif
        </div>
      @endforeach
    </div>
  </div>
</section>
