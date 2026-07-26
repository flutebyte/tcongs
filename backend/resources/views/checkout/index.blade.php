@extends('layouts.app')

@section('meta_title', 'Checkout | '.($siteSettings['site_name'] ?? 'Estele'))

@section('content')

  <nav class="mx-auto w-full max-w-wrapper px-3 md:px-4 flex flex-wrap items-center gap-1.5 py-4 text-[13px] text-muted" aria-label="Breadcrumb">
    <x-breadcrumb :items="[['label' => 'Cart', 'url' => route('cart.index')], ['label' => 'Checkout']]" />
  </nav>

  <div class="mx-auto w-full max-w-wrapper px-3 md:px-4 pb-10 md:pb-[60px]">
    <h1 class="text-[20px] uppercase tracking-[0.5px] md:text-[26px] mb-5">Checkout</h1>

    <div class="grid grid-cols-1 gap-6 md:grid-cols-[1fr_340px] md:gap-[34px]">
      <form action="{{ route('checkout.store') }}" method="post" class="[&_.field-set]:mb-7">
        @csrf

        <fieldset class="field-set">
          <legend class="mb-3.5 text-[14px] font-medium uppercase tracking-[0.5px]">Contact</legend>
          <label class="mb-1.5 block text-[13px] font-medium text-heading" for="customer_email">Email</label>
          <input class="w-full border border-line-strong bg-white px-4 py-3 text-[14px] outline-none transition-colors placeholder:text-muted focus:border-heading mb-3.5" id="customer_email" name="customer_email" type="email" placeholder="you@example.com" value="{{ old('customer_email') }}" required>
          @error('customer_email') <p class="mb-3.5 -mt-2 text-[12px] text-salebadge">{{ $message }}</p> @enderror
          <label class="mb-1.5 block text-[13px] font-medium text-heading" for="customer_phone">Phone</label>
          <input class="w-full border border-line-strong bg-white px-4 py-3 text-[14px] outline-none transition-colors placeholder:text-muted focus:border-heading mb-3.5" id="customer_phone" name="customer_phone" type="tel" placeholder="+91" value="{{ old('customer_phone') }}" required>
          @error('customer_phone') <p class="mb-3.5 -mt-2 text-[12px] text-salebadge">{{ $message }}</p> @enderror
        </fieldset>

        <fieldset class="field-set">
          <legend class="mb-3.5 text-[14px] font-medium uppercase tracking-[0.5px]">Shipping Address</legend>
          <div class="mb-3.5 grid grid-cols-1 gap-3 sm:grid-cols-2">
            <div>
              <label class="mb-1.5 block text-[13px] font-medium text-heading" for="customer_first_name">First name</label>
              <input class="w-full border border-line-strong bg-white px-4 py-3 text-[14px] outline-none transition-colors placeholder:text-muted focus:border-heading" id="customer_first_name" name="customer_first_name" type="text" value="{{ old('customer_first_name') }}" required>
              @error('customer_first_name') <p class="mt-1 text-[12px] text-salebadge">{{ $message }}</p> @enderror
            </div>
            <div>
              <label class="mb-1.5 block text-[13px] font-medium text-heading" for="customer_last_name">Last name</label>
              <input class="w-full border border-line-strong bg-white px-4 py-3 text-[14px] outline-none transition-colors placeholder:text-muted focus:border-heading" id="customer_last_name" name="customer_last_name" type="text" value="{{ old('customer_last_name') }}" required>
              @error('customer_last_name') <p class="mt-1 text-[12px] text-salebadge">{{ $message }}</p> @enderror
            </div>
          </div>
          <label class="mb-1.5 block text-[13px] font-medium text-heading" for="shipping_address_line1">Address</label>
          <input class="w-full border border-line-strong bg-white px-4 py-3 text-[14px] outline-none transition-colors placeholder:text-muted focus:border-heading mb-3.5" id="shipping_address_line1" name="shipping_address_line1" type="text" value="{{ old('shipping_address_line1') }}" required>
          @error('shipping_address_line1') <p class="mb-3.5 -mt-2 text-[12px] text-salebadge">{{ $message }}</p> @enderror
          <div class="mb-3.5 grid grid-cols-1 gap-3 sm:grid-cols-2">
            <div>
              <label class="mb-1.5 block text-[13px] font-medium text-heading" for="shipping_city">City</label>
              <input class="w-full border border-line-strong bg-white px-4 py-3 text-[14px] outline-none transition-colors placeholder:text-muted focus:border-heading" id="shipping_city" name="shipping_city" type="text" value="{{ old('shipping_city') }}" required>
              @error('shipping_city') <p class="mt-1 text-[12px] text-salebadge">{{ $message }}</p> @enderror
            </div>
            <div>
              <label class="mb-1.5 block text-[13px] font-medium text-heading" for="shipping_postal_code">PIN code</label>
              <input class="w-full border border-line-strong bg-white px-4 py-3 text-[14px] outline-none transition-colors placeholder:text-muted focus:border-heading" id="shipping_postal_code" name="shipping_postal_code" type="text" value="{{ old('shipping_postal_code') }}" required>
              @error('shipping_postal_code') <p class="mt-1 text-[12px] text-salebadge">{{ $message }}</p> @enderror
            </div>
          </div>
          <label class="mb-1.5 block text-[13px] font-medium text-heading" for="shipping_state">State</label>
          <select class="w-full border border-line-strong bg-white px-4 py-3 text-[14px] outline-none transition-colors placeholder:text-muted focus:border-heading" id="shipping_state" name="shipping_state" required>
            @foreach(['Telangana', 'Maharashtra', 'Karnataka', 'Delhi'] as $state)
              <option value="{{ $state }}" {{ old('shipping_state') === $state ? 'selected' : '' }}>{{ $state }}</option>
            @endforeach
          </select>
          @error('shipping_state') <p class="mt-1 text-[12px] text-salebadge">{{ $message }}</p> @enderror
        </fieldset>

        <fieldset class="field-set">
          <legend class="mb-3.5 text-[14px] font-medium uppercase tracking-[0.5px]">Payment</legend>
          <ul class="space-y-2.5">
            <li>
              <label class="flex cursor-not-allowed items-center gap-2.5 border border-line-strong p-3.5 text-[14px] opacity-50">
                <input class="accent-accent" type="radio" name="pay" disabled> UPI / Card / Netbanking
              </label>
            </li>
            <li>
              <label class="flex cursor-pointer items-center gap-2.5 border border-line-strong p-3.5 text-[14px]">
                <input class="accent-accent" type="radio" name="pay" checked> Cash on Delivery
              </label>
            </li>
          </ul>
          <p class="mt-2 text-[12px] text-muted">Online payment is coming soon.</p>
        </fieldset>

        <div class="field-set">
          <label class="mb-1.5 block text-[13px] font-medium text-heading" for="order_note">Order note (optional)</label>
          <textarea class="w-full border border-line-strong bg-white px-4 py-3 text-[14px] outline-none transition-colors placeholder:text-muted focus:border-heading" id="order_note" name="order_note" rows="3">{{ old('order_note') }}</textarea>
        </div>

        <button class="inline-flex items-center justify-center gap-2 border border-black bg-black px-8 py-[13px] text-[13px] font-medium uppercase tracking-[0.5px] text-white transition-colors hover:border-accent hover:bg-accent w-full" type="submit">Place Order</button>
      </form>

      <aside class="rounded bg-pinksoft p-6 md:sticky md:top-[100px]">
        <h2 class="mb-4.5 text-[15px] uppercase tracking-[0.5px]">Order Summary</h2>
        <div class="mb-4.5 divide-y divide-line-strong/40">
          @foreach($items as $item)
            <div class="flex items-center justify-between gap-3 py-2 text-[13px]">
              <span class="text-heading">{{ $item->product->title }} &times; {{ $item->quantity }}</span>
              <span class="shrink-0 font-medium">₹{{ number_format($item->unitPrice() * $item->quantity, 0) }}</span>
            </div>
          @endforeach
        </div>
        <dl class="mb-4.5 space-y-2 text-[14px]">
          <div class="flex justify-between"><dt class="text-muted">Subtotal</dt><dd>₹{{ number_format($subtotal, 0) }}</dd></div>
          @if($discount > 0)
            <div class="flex justify-between text-[#1a7d3f]"><dt>Discount ({{ $couponCode }})</dt><dd>&minus;₹{{ number_format($discount, 0) }}</dd></div>
          @endif
          <div class="flex justify-between"><dt class="text-muted">Shipping</dt><dd>Free</dd></div>
          <div class="flex justify-between border-t border-line-strong pt-3.5 text-[16px] font-medium"><dt class="text-heading">Total</dt><dd>₹{{ number_format($subtotal - $discount, 0) }}</dd></div>
        </dl>

        @include('partials.offers-banner')

        <div class="mb-1">
          <div class="mb-1.5 flex items-center justify-between">
            <label class="text-[13px] font-medium text-heading" for="checkout-coupon">Coupon code</label>
            <button class="text-[12px] text-muted underline transition-colors hover:text-accent" type="button" data-coupons-modal-open>View all coupons</button>
          </div>
          @if($couponCode)
            <div class="flex items-center justify-between border border-line-strong bg-white px-4 py-3 text-[14px]">
              <span>Applied: <strong>{{ $couponCode }}</strong></span>
              <form action="{{ route('cart.coupon.remove') }}" method="post">
                @csrf
                @method('delete')
                <button class="text-[12px] text-muted underline transition-colors hover:text-[#eb001b]" type="submit">Remove</button>
              </form>
            </div>
          @else
            <form action="{{ route('cart.coupon.apply') }}" method="post" class="flex gap-2">
              @csrf
              <input class="w-full border border-line-strong bg-white px-4 py-3 text-[14px] outline-none transition-colors placeholder:text-muted focus:border-heading flex-1" id="checkout-coupon" name="code" type="text" placeholder="Enter code" required>
              <button class="inline-flex items-center justify-center gap-2 border border-black bg-black px-5 py-3 text-[12px] font-medium uppercase tracking-[0.5px] text-white transition-colors hover:border-accent hover:bg-accent" type="submit">Apply</button>
            </form>
          @endif
        </div>
      </aside>
    </div>
  </div>

@endsection
