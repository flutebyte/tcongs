@extends('layouts.app')

@section('meta_title', 'Login with Mobile Number | '.($siteSettings['site_name'] ?? 'Estele'))
@section('meta_description', 'Login or create an account using your mobile number and a one-time code.')

@section('content')

  <div class="mx-auto w-full max-w-[420px] px-3 py-12 md:px-4 md:py-16">
    <h1 class="mb-1 text-[22px] uppercase tracking-[0.5px] md:text-[26px]">Login</h1>
    <p class="mb-5 text-[13px] text-muted">We'll text a one-time code to verify your number.</p>

    <div class="mb-6 flex rounded-lg border border-line p-1 text-[12px] font-medium uppercase tracking-[0.3px]">
      <a class="flex-1 rounded-md py-2.5 text-center text-heading transition-colors hover:bg-pinksoft" href="{{ route('login') }}">Email &amp; Password</a>
      <span class="flex-1 rounded-md bg-black py-2.5 text-center text-white">Mobile Number (OTP)</span>
    </div>

    <form action="{{ route('login.mobile.send') }}" method="post">
      @csrf

      <label class="mb-1.5 block text-[13px] font-medium text-heading" for="phone">Mobile number</label>
      <input class="w-full border border-line-strong bg-white px-4 py-3 text-[14px] outline-none transition-colors placeholder:text-muted focus:border-heading mb-3.5" id="phone" name="phone" type="tel" value="{{ old('phone') }}" placeholder="10-digit mobile number" inputmode="numeric" required autofocus>
      @error('phone') <p class="mb-3.5 -mt-2 text-[12px] text-salebadge">{{ $message }}</p> @enderror

      <button class="inline-flex w-full items-center justify-center gap-2 border border-black bg-black px-8 py-[13px] text-[13px] font-medium uppercase tracking-[0.5px] text-white transition-colors hover:border-accent hover:bg-accent" type="submit">
        Send OTP
      </button>
    </form>

    <p class="mt-6 text-center text-[13px] text-muted">
      New here? You'll be guided to finish creating an account right after your number is verified.
    </p>
  </div>

@endsection
