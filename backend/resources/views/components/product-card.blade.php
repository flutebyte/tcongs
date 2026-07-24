@props(['product'])

<article class="group relative text-center">
  <a class="relative block aspect-square overflow-hidden bg-placeholder" href="{{ route('products.show', $product) }}" aria-label="{{ $product->title }}">
    @if($product->hasMedia('gallery'))
      <img class="h-full w-full object-cover" src="{{ $product->getFirstMediaUrl('gallery', 'card') }}" alt="{{ $product->title }}" loading="lazy" width="600" height="600">
      @if($product->getMedia('gallery')->count() > 1)
        <img class="absolute inset-0 h-full w-full object-cover opacity-0 transition-opacity duration-300 group-hover:opacity-100" src="{{ $product->getMedia('gallery')[1]->getUrl('card') }}" alt="" loading="lazy" width="600" height="600">
      @endif
    @endif
    @if($product->compare_at_price)
      <span class="absolute left-2.5 top-2.5 z-[2] flex flex-col gap-1.5">
        <span class="inline-block bg-salebadge px-2.5 py-1 text-[11px] font-medium uppercase leading-none tracking-[0.3px] text-white">Sale</span>
      </span>
    @endif
    <button class="absolute right-2.5 top-2.5 z-[2] grid h-[34px] w-[34px] place-items-center rounded-full bg-white opacity-100 md:opacity-0 transition-opacity md:group-hover:opacity-100" type="button" aria-label="Add to wishlist" data-product-id="{{ $product->id }}">
      <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1-1.1a5.5 5.5 0 0 0-7.8 7.8l1.1 1.1L12 21.2l7.7-7.7 1.1-1.1a5.5 5.5 0 0 0 0-7.8z"/></svg>
    </button>
  </a>
  <div class="pt-3">
    <h3 class="mb-1.5 text-[14px] font-normal leading-normal text-heading">
      <a class="transition-colors hover:text-accent" href="{{ route('products.show', $product) }}">{{ $product->title }}</a>
    </h3>
    <div class="flex flex-wrap items-center justify-center gap-2">
      <span class="font-medium text-price">₹{{ number_format($product->price, 0) }}</span>
      @if($product->compare_at_price)
        <span class="text-muted line-through">₹{{ number_format($product->compare_at_price, 0) }}</span>
      @endif
    </div>
  </div>
</article>
