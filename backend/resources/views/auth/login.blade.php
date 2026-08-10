@extends('layouts.app')

@section('meta_title', 'Login | '.($siteSettings['site_name'] ?? 'Estele'))
@section('meta_description', 'Login to your account to track orders and check out faster.')

@section('content')

  <div class="mx-auto w-full max-w-[420px] px-3 py-12 md:px-4 md:py-16">
    <h1 class="mb-1 text-[22px] uppercase tracking-[0.5px] md:text-[26px]">Login</h1>
    <p class="mb-5 text-[13px] text-muted">Welcome back — sign in to view your orders.</p>

    <div class="mb-6 flex rounded-lg border border-line p-1 text-[12px] font-medium uppercase tracking-[0.3px]">
      <span class="flex-1 rounded-md bg-black py-2.5 text-center text-white">Email &amp; Password</span>
      <a class="flex-1 rounded-md py-2.5 text-center text-heading transition-colors hover:bg-pinksoft" href="{{ route('login.mobile') }}">Mobile Number (OTP)</a>
    </div>

    <form action="{{ route('login.attempt') }}" method="post">
      @csrf

      <label class="mb-1.5 block text-[13px] font-medium text-heading" for="email">Email</label>
      <input class="w-full border border-line-strong bg-white px-4 py-3 text-[14px] outline-none transition-colors placeholder:text-muted focus:border-heading mb-3.5" id="email" name="email" type="email" value="{{ old('email') }}" required autofocus>
      @error('email') <p class="mb-3.5 -mt-2 text-[12px] text-salebadge">{{ $message }}</p> @enderror

      <label class="mb-1.5 block text-[13px] font-medium text-heading" for="password">Password</label>
      <input class="w-full border border-line-strong bg-white px-4 py-3 text-[14px] outline-none transition-colors placeholder:text-muted focus:border-heading mb-3.5" id="password" name="password" type="password" required>
      @error('password') <p class="mb-3.5 -mt-2 text-[12px] text-salebadge">{{ $message }}</p> @enderror

      <label class="mb-5 flex items-center gap-2 text-[13px] text-muted">
        <input class="h-4 w-4 border-line-strong" type="checkbox" name="remember">
        Remember me
      </label>

      <button class="inline-flex w-full items-center justify-center gap-2 border border-black bg-black px-8 py-[13px] text-[13px] font-medium uppercase tracking-[0.5px] text-white transition-colors hover:border-accent hover:bg-accent" type="submit">
        Login
      </button>
    </form>

    <p class="mt-6 text-center text-[13px] text-muted">
      New here? <a class="font-medium text-heading underline hover:text-accent" href="{{ route('register') }}">Create an account</a>
    </p>
  </div>

@endsection
