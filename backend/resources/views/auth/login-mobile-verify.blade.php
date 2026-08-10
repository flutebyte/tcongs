@extends('layouts.app')

@section('meta_title', 'Verify Code | '.($siteSettings['site_name'] ?? 'Estele'))

@section('content')

  <div class="mx-auto w-full max-w-[420px] px-3 py-12 md:px-4 md:py-16">
    <h1 class="mb-1 text-[22px] uppercase tracking-[0.5px] md:text-[26px]">Enter Code</h1>
    <p class="mb-7 text-[13px] text-muted">We sent a 6-digit code to <strong class="text-heading">{{ $phone }}</strong>.</p>

    <form action="{{ route('login.mobile.verify.attempt') }}" method="post">
      @csrf

      <label class="mb-1.5 block text-[13px] font-medium text-heading" for="code">One-time code</label>
      {{-- tracking-[0.5em] has no live Tailwind build to compile it here (see
           filter-panel/account-layout-grid comments elsewhere) — inline style instead. --}}
      <input class="w-full border border-line-strong bg-white px-4 py-3 text-center text-[20px] outline-none transition-colors placeholder:text-muted focus:border-heading mb-3.5" style="letter-spacing: 0.5em" id="code" name="code" type="text" inputmode="numeric" pattern="[0-9]*" maxlength="6" placeholder="&middot;&middot;&middot;&middot;&middot;&middot;" required autofocus>
      @error('code') <p class="mb-3.5 -mt-2 text-[12px] text-salebadge">{{ $message }}</p> @enderror

      <button class="inline-flex w-full items-center justify-center gap-2 border border-black bg-black px-8 py-[13px] text-[13px] font-medium uppercase tracking-[0.5px] text-white transition-colors hover:border-accent hover:bg-accent" type="submit">
        Verify &amp; Continue
      </button>
    </form>

    <form class="mt-4" action="{{ route('login.mobile.resend') }}" method="post">
      @csrf
      <button class="w-full text-center text-[13px] text-muted underline hover:text-accent" type="submit">Resend code</button>
    </form>

    <p class="mt-6 text-center text-[13px] text-muted">
      Wrong number? <a class="font-medium text-heading underline hover:text-accent" href="{{ route('login.mobile') }}">Start over</a>
    </p>
  </div>

@endsection
