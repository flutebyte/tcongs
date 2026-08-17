@props(['block'])

@if($block->items->isNotEmpty())
  <section class="py-10 md:py-[60px] bg-warmbeige/40">
    <div class="mx-auto w-full max-w-wrapper px-3 md:px-4">
      <div class="pb-8 pt-2 text-center md:pb-[34px]">
        <h2 class="mb-2.5 flex flex-wrap items-center justify-center gap-2 md:gap-3.5">
          <span class="text-[18px] font-serif font-semibold uppercase tracking-[2px] text-heading md:text-[26px]">As Seen On</span>
          <span class="font-serif text-[24px] font-semibold text-gold md:text-[38px]">Celebrities</span>
        </h2>
        @if($block->subtitle)
          <p class="mx-auto mb-5 max-w-[34ch] text-[14px] text-heading md:text-[18px]">{{ $block->subtitle }}</p>
        @endif
        @if($block->cta_label)
          <a class="inline-flex items-center justify-center rounded-md bg-accent px-8 py-3 text-[14px] font-medium text-white transition-colors hover:bg-accent-dark" href="{{ $block->cta_url ?? '#' }}">{{ $block->cta_label }}</a>
        @endif
      </div>
      <div class="grid grid-cols-2 gap-3 md:grid-cols-4 md:gap-5">
        @foreach($block->items as $item)
          <a class="block" href="{{ $item->link_url ?? '#' }}">
            <span class="block overflow-hidden bg-placeholder rounded-2xl" style="aspect-ratio: 1/1.38;">
              @if($item->hasMedia('image'))
                <img class="h-full w-full object-cover transition-transform duration-700 hover:scale-105"
                     src="{{ $item->getFirstMediaUrl('image', 'card') }}"
                     alt="{{ $item->title }}" loading="lazy" width="500" height="750">
              @endif
            </span>
          </a>
        @endforeach
      </div>
    </div>
  </section>
@endif
