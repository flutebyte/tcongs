@extends('layouts.app')

@section('meta_title', 'Order Confirmed | '.($siteSettings['site_name'] ?? 'Estele'))

@section('content')

  <div class="mx-auto w-full max-w-[640px] px-3 py-12 text-center md:px-4 md:py-16">
    <div class="mx-auto mb-5 grid h-14 w-14 place-items-center rounded-full bg-pinksoft text-accent">
      <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6 9 17l-5-5"/></svg>
    </div>
    <h1 class="mb-2 text-[22px] md:text-[28px]">Thank you, {{ $order->customer_name }}!</h1>
    <p class="mb-1 text-[14px] text-muted">Your order has been placed successfully.</p>
    <p class="mb-8 text-[14px] font-medium text-heading">Order Number: {{ $order->order_number }}</p>

    <div class="mb-8 rounded-lg border border-line p-5 text-left">
      <h2 class="mb-4 text-[13px] font-medium uppercase tracking-[0.3px]">Order Details</h2>
      <div class="divide-y divide-line">
        @foreach($order->items as $item)
          <div class="flex items-center justify-between gap-3 py-2.5 text-[13px]">
            <span class="text-heading">{{ $item->product_title }} &times; {{ $item->quantity }}</span>
            <span class="shrink-0 font-medium text-price">₹{{ number_format($item->subtotal, 0) }}</span>
          </div>
        @endforeach
      </div>
      @if($order->discount_amount > 0)
        <div class="flex items-center justify-between py-2.5 text-[13px] text-[#1a7d3f]">
          <span>Discount ({{ $order->coupon_code }})</span>
          <span>&minus;₹{{ number_format($order->discount_amount, 0) }}</span>
        </div>
      @endif
      <div class="flex items-center justify-between py-2.5 text-[13px]">
        <span class="text-muted">Shipping</span>
        <span>{{ $order->shipping_fee > 0 ? '₹'.number_format($order->shipping_fee, 0) : 'Free' }}</span>
      </div>
      <div class="flex items-center justify-between border-t border-line pt-3 text-[15px]">
        <span class="font-medium text-heading">Total</span>
        <span class="font-medium text-price">₹{{ number_format($order->total, 0) }}</span>
      </div>
      <p class="mt-4 text-[12px] text-muted">Payment method: Cash on Delivery</p>
      <p class="mt-3 text-[13px] text-muted">
        Shipping to {{ $order->shipping_address_line1 }}{{ $order->shipping_address_line2 ? ', '.$order->shipping_address_line2 : '' }},
        {{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_postal_code }}, {{ $order->shipping_country }}
      </p>
    </div>

    <a class="inline-flex items-center justify-center gap-2 border border-black bg-black px-8 py-[13px] text-[13px] font-medium uppercase tracking-[0.5px] text-white transition-colors hover:border-accent hover:bg-accent" href="{{ route('home') }}">
      Continue Shopping
    </a>
  </div>

@endsection
