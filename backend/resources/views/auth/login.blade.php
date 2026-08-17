@extends('layouts.app')

@section('meta_title', 'Login | '.($siteSettings['site_name'] ?? 'Estele'))
@section('meta_description', 'Login to your account to track orders and check out faster.')

@section('content')

<div class="bg-ivory py-10 md:py-16 min-h-[calc(100vh-220px)]">
  <div class="mx-auto w-full max-w-[450px] px-4">
    <div class="rounded-2xl border border-line bg-white p-6 sm:p-8 shadow-md">

      <h1 class="font-serif text-[24px] font-semibold text-heading text-center mb-1">Welcome Back</h1>
      <p class="text-[13px] text-muted text-center mb-6">Log in to view your orders, wishlist &amp; rewards.</p>

      <div class="mb-6 flex rounded-lg border border-line p-1 text-[12px] font-medium uppercase tracking-[0.3px]">
        <span class="flex-1 rounded-md bg-accent py-2.5 text-center text-white font-semibold">Email &amp; Password</span>
        <a class="flex-1 rounded-md py-2.5 text-center text-muted transition-colors hover:text-heading" href="{{ route('login.mobile') }}">Mobile Number (OTP)</a>
      </div>

      <form action="{{ route('login.attempt') }}" method="post" class="space-y-4">
        @csrf

        <div>
          <label class="mb-1.5 block text-[13px] font-medium text-heading" for="email">Email Address</label>
          <input class="h-12 w-full rounded-lg border border-line-strong bg-white px-4 text-[14px] outline-none transition-colors placeholder:text-muted focus:border-accent focus:ring-1 focus:ring-accent" id="email" name="email" type="email" value="{{ old('email') }}" placeholder="name@example.com" required autofocus>
          @error('email') <p class="mt-1 text-[12px] text-salebadge">{{ $message }}</p> @enderror
        </div>

        <div>
          <div class="flex items-center justify-between mb-1.5">
            <label class="text-[13px] font-medium text-heading" for="password">Password</label>
            <a class="text-[12px] font-medium text-accent hover:underline" href="{{ route('password.request') ?? '#' }}">Forgot Password?</a>
          </div>
          <input class="h-12 w-full rounded-lg border border-line-strong bg-white px-4 text-[14px] outline-none transition-colors placeholder:text-muted focus:border-accent focus:ring-1 focus:ring-accent" id="password" name="password" type="password" placeholder="••••••••" required>
          @error('password') <p class="mt-1 text-[12px] text-salebadge">{{ $message }}</p> @enderror
        </div>

        <div class="flex items-center justify-between pt-1">
          <label class="flex items-center gap-2 text-[13px] text-muted">
            <input class="h-4 w-4 rounded border-line-strong text-accent focus:ring-accent" type="checkbox" name="remember">
            Remember me
          </label>
        </div>

        <button class="mt-2 h-12 w-full rounded-lg bg-accent text-[13px] font-semibold uppercase tracking-[0.6px] text-white shadow-sm transition-colors hover:bg-accent-dark" type="submit">
          Log In
        </button>
      </form>

      <p class="mt-6 text-center text-[13px] text-muted">
        New to Estele? <a class="font-semibold text-accent underline hover:text-accent-dark" href="{{ route('register') }}">Create an account</a>
      </p>

    </div>
  </div>
</div>

@endsection
