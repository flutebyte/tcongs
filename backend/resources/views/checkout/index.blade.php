@extends('layouts.app')

@section('meta_title', 'Checkout | '.($siteSettings['site_name'] ?? 'Estele'))

@section('content')

  <div class="mx-auto w-full max-w-wrapper px-3 py-8 md:px-4 md:py-10">
    <h1 class="mb-6 text-[22px] md:text-[28px]">Checkout</h1>

    <form action="{{ route('checkout.store') }}" method="post" class="grid grid-cols-1 gap-8 lg:grid-cols-[1fr_320px]">
      @csrf

      <div class="space-y-6">
        <div class="rounded-lg border border-line p-5">
          <h2 class="mb-4 text-[15px] font-medium uppercase tracking-[0.3px]">Contact Details</h2>
          <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <div class="md:col-span-2">
              <label class="mb-1.5 block text-[12px] uppercase tracking-[0.3px] text-muted" for="customer_name">Full Name</label>
              <input class="w-full border border-line-strong px-3.5 py-2.5 text-[14px] outline-none focus:border-heading" type="text" id="customer_name" name="customer_name" value="{{ old('customer_name') }}" required>
              @error('customer_name') <p class="mt-1 text-[12px] text-salebadge">{{ $message }}</p> @enderror
            </div>
            <div>
              <label class="mb-1.5 block text-[12px] uppercase tracking-[0.3px] text-muted" for="customer_email">Email</label>
              <input class="w-full border border-line-strong px-3.5 py-2.5 text-[14px] outline-none focus:border-heading" type="email" id="customer_email" name="customer_email" value="{{ old('customer_email') }}" required>
              @error('customer_email') <p class="mt-1 text-[12px] text-salebadge">{{ $message }}</p> @enderror
            </div>
            <div>
              <label class="mb-1.5 block text-[12px] uppercase tracking-[0.3px] text-muted" for="customer_phone">Phone</label>
              <input class="w-full border border-line-strong px-3.5 py-2.5 text-[14px] outline-none focus:border-heading" type="tel" id="customer_phone" name="customer_phone" value="{{ old('customer_phone') }}" required>
              @error('customer_phone') <p class="mt-1 text-[12px] text-salebadge">{{ $message }}</p> @enderror
            </div>
          </div>
        </div>

        <div class="rounded-lg border border-line p-5">
          <h2 class="mb-4 text-[15px] font-medium uppercase tracking-[0.3px]">Shipping Address</h2>
          <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <div class="md:col-span-2">
              <label class="mb-1.5 block text-[12px] uppercase tracking-[0.3px] text-muted" for="shipping_address_line1">Address Line 1</label>
              <input class="w-full border border-line-strong px-3.5 py-2.5 text-[14px] outline-none focus:border-heading" type="text" id="shipping_address_line1" name="shipping_address_line1" value="{{ old('shipping_address_line1') }}" required>
              @error('shipping_address_line1') <p class="mt-1 text-[12px] text-salebadge">{{ $message }}</p> @enderror
            </div>
            <div class="md:col-span-2">
              <label class="mb-1.5 block text-[12px] uppercase tracking-[0.3px] text-muted" for="shipping_address_line2">Address Line 2 (optional)</label>
              <input class="w-full border border-line-strong px-3.5 py-2.5 text-[14px] outline-none focus:border-heading" type="text" id="shipping_address_line2" name="shipping_address_line2" value="{{ old('shipping_address_line2') }}">
            </div>
            <div>
              <label class="mb-1.5 block text-[12px] uppercase tracking-[0.3px] text-muted" for="shipping_city">City</label>
              <input class="w-full border border-line-strong px-3.5 py-2.5 text-[14px] outline-none focus:border-heading" type="text" id="shipping_city" name="shipping_city" value="{{ old('shipping_city') }}" required>
              @error('shipping_city') <p class="mt-1 text-[12px] text-salebadge">{{ $message }}</p> @enderror
            </div>
            <div>
              <label class="mb-1.5 block text-[12px] uppercase tracking-[0.3px] text-muted" for="shipping_state">State</label>
              <input class="w-full border border-line-strong px-3.5 py-2.5 text-[14px] outline-none focus:border-heading" type="text" id="shipping_state" name="shipping_state" value="{{ old('shipping_state') }}" required>
              @error('shipping_state') <p class="mt-1 text-[12px] text-salebadge">{{ $message }}</p> @enderror
            </div>
            <div>
              <label class="mb-1.5 block text-[12px] uppercase tracking-[0.3px] text-muted" for="shipping_postal_code">Postal Code</label>
              <input class="w-full border border-line-strong px-3.5 py-2.5 text-[14px] outline-none focus:border-heading" type="text" id="shipping_postal_code" name="shipping_postal_code" value="{{ old('shipping_postal_code') }}" required>
              @error('shipping_postal_code') <p class="mt-1 text-[12px] text-salebadge">{{ $message }}</p> @enderror
            </div>
            <div>
              <label class="mb-1.5 block text-[12px] uppercase tracking-[0.3px] text-muted">Country</label>
              <input class="w-full border border-line-strong bg-placeholder px-3.5 py-2.5 text-[14px] text-muted" type="text" value="India" disabled>
            </div>
          </div>
        </div>

        <div class="rounded-lg border border-line p-5">
          <label class="mb-1.5 block text-[12px] uppercase tracking-[0.3px] text-muted" for="order_note">Order Note (optional)</label>
          <textarea class="w-full border border-line-strong px-3.5 py-2.5 text-[14px] outline-none focus:border-heading" id="order_note" name="order_note" rows="3">{{ old('order_note') }}</textarea>
        </div>
      </div>

      <div class="h-fit space-y-5 rounded-lg border border-line p-5">
        <h2 class="text-[15px] font-medium uppercase tracking-[0.3px]">Order Summary</h2>
        <div class="divide-y divide-line">
          @foreach($items as $item)
            <div class="flex items-center justify-between gap-3 py-2.5 text-[13px]">
              <span class="text-heading">{{ $item->product->title }} &times; {{ $item->quantity }}</span>
              <span class="shrink-0 font-medium text-price">₹{{ number_format($item->unitPrice() * $item->quantity, 0) }}</span>
            </div>
          @endforeach
        </div>
        <div class="flex items-center justify-between border-t border-line pt-3 text-[14px]">
          <span class="text-muted">Subtotal</span>
          <span class="font-medium text-price">₹{{ number_format($subtotal, 0) }}</span>
        </div>
        <div class="flex items-center justify-between text-[14px]">
          <span class="text-muted">Shipping</span>
          <span class="font-medium text-price">Free</span>
        </div>
        <div class="flex items-center justify-between border-t border-line pt-3 text-[16px]">
          <span class="font-medium text-heading">Total</span>
          <span class="font-medium text-price">₹{{ number_format($subtotal, 0) }}</span>
        </div>

        <div class="border-t border-line pt-4">
          <p class="mb-3 text-[12px] font-medium uppercase tracking-[0.3px] text-muted">Payment Method</p>
          <label class="flex items-center gap-2 text-[13px] text-heading">
            <input type="radio" name="payment_method" value="cod" checked disabled>
            Cash on Delivery
          </label>
          <p class="mt-1 text-[11px] text-muted">Online payment is coming soon.</p>
        </div>

        <button class="flex w-full items-center justify-center gap-2 border border-black bg-black px-8 py-[13px] text-[13px] font-medium uppercase tracking-[0.5px] text-white transition-colors hover:border-accent hover:bg-accent" type="submit">
          Place Order
        </button>
      </div>
    </form>
  </div>

@endsection
