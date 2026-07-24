@if($items->isEmpty())
  <div class="flex flex-1 flex-col items-center justify-center gap-4 px-6 py-16 text-center">
    <p class="text-[14px] text-muted">Your cart is empty.</p>
    <a class="inline-flex items-center justify-center gap-2 border border-black bg-black px-6 py-3 text-[12px] font-medium uppercase tracking-[0.5px] text-white transition-colors hover:border-accent hover:bg-accent" href="{{ route('home') }}" data-cart-close>
      Continue Shopping
    </a>
  </div>
@else
  <div class="flex-1 divide-y divide-line overflow-y-auto px-5">
    @foreach($items as $item)
      <div class="flex gap-3 py-4" data-cart-drawer-item="{{ $item->id }}">
        <a class="block aspect-square w-16 shrink-0 overflow-hidden rounded bg-placeholder" href="{{ route('products.show', $item->product) }}">
          @if($item->product->hasMedia('gallery'))
            <img class="h-full w-full object-cover" src="{{ $item->product->getFirstMediaUrl('gallery', 'card') }}" alt="{{ $item->product->title }}" width="128" height="128">
          @endif
        </a>
        <div class="flex flex-1 flex-col justify-between">
          <div>
            <a class="text-[13px] leading-snug text-heading transition-colors hover:text-accent" href="{{ route('products.show', $item->product) }}">{{ $item->product->title }}</a>
            @if($item->variant)
              <p class="mt-0.5 text-[11.5px] text-muted">{{ collect($item->variant->attributes ?? [])->map(fn($v, $k) => "{$k}: {$v}")->implode(', ') }}</p>
            @endif
            <p class="mt-1 flex items-center gap-1.5 text-[13px]">
              <span class="font-medium text-price">₹{{ number_format($item->unitPrice(), 0) }}</span>
              @if($item->product->compare_at_price)
                <span class="text-[11.5px] text-muted line-through">₹{{ number_format($item->product->compare_at_price, 0) }}</span>
              @endif
            </p>
          </div>
          <div class="flex items-center justify-between">
            <div class="inline-flex items-center border border-line-strong" data-cart-qty-stepper data-item-id="{{ $item->id }}" data-max="{{ $item->availableStock() }}">
              <button class="grid h-8 w-8 place-items-center text-[15px] text-heading transition-colors hover:text-accent" type="button" data-cart-qty-decrement aria-label="Decrease quantity">&minus;</button>
              <span class="grid w-8 place-items-center text-[13px]" data-cart-qty-value>{{ $item->quantity }}</span>
              <button class="grid h-8 w-8 place-items-center text-[15px] text-heading transition-colors hover:text-accent" type="button" data-cart-qty-increment aria-label="Increase quantity">&plus;</button>
            </div>
            <button class="text-[11px] uppercase tracking-[0.3px] text-muted transition-colors hover:text-salebadge" type="button" data-cart-remove data-item-id="{{ $item->id }}">
              Remove
            </button>
          </div>
        </div>
      </div>
    @endforeach
  </div>

  <div class="border-t border-line px-5 py-4">
    <div class="mb-4 flex items-center justify-between text-[15px]">
      <span class="font-medium uppercase tracking-[0.3px]">Subtotal</span>
      <span class="font-medium text-price" data-cart-subtotal>₹{{ number_format($subtotal, 0) }}</span>
    </div>
    <div class="flex flex-col gap-2">
      <a class="flex items-center justify-center gap-2 border border-line-strong px-6 py-3 text-[12px] font-medium uppercase tracking-[0.5px] text-heading transition-colors hover:border-accent hover:text-accent" href="{{ route('cart.index') }}">
        View Cart
      </a>
      <a class="flex items-center justify-center gap-2 border border-black bg-black px-6 py-3 text-[12px] font-medium uppercase tracking-[0.5px] text-white transition-colors hover:border-accent hover:bg-accent" href="{{ route('checkout.index') }}">
        Checkout
      </a>
    </div>
  </div>
@endif
