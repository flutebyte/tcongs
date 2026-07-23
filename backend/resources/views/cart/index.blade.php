@extends('layouts.app')

@section('meta_title', 'Shopping Cart | '.($siteSettings['site_name'] ?? 'Estele'))

@section('content')

  <div class="mx-auto w-full max-w-wrapper px-3 py-8 md:px-4 md:py-10">
    <h1 class="mb-6 text-[22px] md:text-[28px]">Shopping Cart</h1>

    @if($items->isEmpty())
      <div class="rounded-lg border border-line py-16 text-center">
        <p class="mb-5 text-[14px] text-muted">Your cart is empty.</p>
        <a class="inline-flex items-center justify-center gap-2 border border-black bg-black px-8 py-[13px] text-[13px] font-medium uppercase tracking-[0.5px] text-white transition-colors hover:border-accent hover:bg-accent" href="{{ route('home') }}">
          Continue Shopping
        </a>
      </div>
    @else
      <div class="grid grid-cols-1 gap-8 lg:grid-cols-[1fr_320px]">
        <div class="divide-y divide-line border-y border-line">
          @foreach($items as $item)
            <div class="flex gap-4 py-5">
              <a class="block aspect-square w-20 shrink-0 overflow-hidden rounded bg-placeholder md:w-24" href="{{ route('products.show', $item->product) }}">
                @if($item->product->hasMedia('gallery'))
                  <img class="h-full w-full object-cover" src="{{ $item->product->getFirstMediaUrl('gallery', 'card') }}" alt="{{ $item->product->title }}" width="200" height="200">
                @endif
              </a>
              <div class="flex flex-1 flex-col justify-between">
                <div>
                  <a class="text-[14px] text-heading transition-colors hover:text-accent" href="{{ route('products.show', $item->product) }}">{{ $item->product->title }}</a>
                  @if($item->variant)
                    <p class="mt-1 text-[12px] text-muted">{{ collect($item->variant->attributes ?? [])->map(fn($v, $k) => "{$k}: {$v}")->implode(', ') }}</p>
                  @endif
                  <p class="mt-1 text-[13px] font-medium text-price">₹{{ number_format($item->unitPrice(), 0) }}</p>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                  <form action="{{ route('cart.update', $item) }}" method="post">
                    @csrf
                    @method('patch')
                    <div class="inline-flex items-center border border-line-strong">
                      <input class="h-9 w-[56px] border-0 text-center text-[13px]" type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="{{ $item->availableStock() }}" aria-label="Quantity">
                      <button class="h-9 border-l border-line-strong px-3 text-[11px] font-medium uppercase tracking-[0.3px] text-heading transition-colors hover:text-accent" type="submit">Update</button>
                    </div>
                  </form>
                  <form action="{{ route('cart.destroy', $item) }}" method="post">
                    @csrf
                    @method('delete')
                    <button class="text-[12px] uppercase tracking-[0.3px] text-muted transition-colors hover:text-salebadge" type="submit">Remove</button>
                  </form>
                </div>
              </div>
              <p class="shrink-0 text-[14px] font-medium text-price">₹{{ number_format($item->unitPrice() * $item->quantity, 0) }}</p>
            </div>
          @endforeach
        </div>

        <div class="h-fit rounded-lg border border-line p-5">
          <h2 class="mb-4 text-[15px] font-medium uppercase tracking-[0.3px]">Order Summary</h2>
          <div class="mb-4 flex items-center justify-between text-[14px]">
            <span class="text-muted">Subtotal</span>
            <span class="font-medium text-price">₹{{ number_format($subtotal, 0) }}</span>
          </div>
          <p class="mb-5 text-[12px] text-muted">Shipping and taxes calculated at checkout.</p>
          <a class="flex items-center justify-center gap-2 border border-black bg-black px-8 py-[13px] text-[13px] font-medium uppercase tracking-[0.5px] text-white transition-colors hover:border-accent hover:bg-accent" href="{{ route('checkout.index') }}">
            Proceed to Checkout
          </a>
        </div>
      </div>
    @endif
  </div>

@endsection
