@props(['block'])

@php $items = $block->items; @endphp

@if($items->isNotEmpty())
  <section class="py-6 md:py-8 bg-pinksoft">
    <div class="mx-auto w-full max-w-wrapper px-3 md:px-4">
      <div class="flex flex-col gap-4 md:flex-row md:items-center md:gap-6">
        <div class="flex-shrink-0 md:w-[180px]">
          <h2 class="text-[22px] font-normal leading-snug md:text-[28px]">Your Budget,<br><b class="font-semibold">Your Bling</b></h2>
        </div>
        <div class="grid grid-cols-2 gap-3 flex-1 md:grid-cols-4 md:gap-4">
          @foreach($items as $item)
            <a class="group relative block overflow-hidden rounded-[14px]" href="{{ $item->link_url ?? route('home') }}" style="height:140px;">
              @if($item->hasMedia('image'))
                <img class="h-full w-full {{ $loop->last ? 'object-cover' : 'object-contain' }} transition-transform duration-300 group-hover:scale-[1.03]" src="{{ $item->getFirstMediaUrl('image', 'card') }}" alt="" loading="lazy">
              @endif
              <span class="pointer-events-none absolute inset-0 flex flex-col items-center justify-center gap-0.5">
                <span class="text-[11px] uppercase tracking-[0.5px] {{ $loop->last ? 'text-white/80' : 'text-muted' }}">{{ $item->title }}</span>
                <span class="text-[22px] font-semibold leading-tight md:text-[26px] {{ $loop->last ? 'text-white' : 'text-heading' }}">{{ $item->body }}</span>
              </span>
              <span class="absolute bottom-[12%] left-1/2 grid h-6 w-6 -translate-x-1/2 place-items-center rounded-full text-white transition-colors {{ $loop->last ? 'bg-white/35' : 'bg-[#f7c7d4] group-hover:bg-accent' }}">
                <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M9 18l6-6-6-6"/></svg>
              </span>
            </a>
          @endforeach
        </div>
      </div>
    </div>
  </section>
@endif
