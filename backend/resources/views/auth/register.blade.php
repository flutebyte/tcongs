@extends('layouts.app')

@section('meta_title', 'Create Account | '.($siteSettings['site_name'] ?? 'Estele'))
@section('meta_description', 'Create an account to track your orders and check out faster.')

@section('content')

  <div class="mx-auto w-full max-w-[420px] px-3 py-12 md:px-4 md:py-16">
    <h1 class="mb-1 text-[22px] uppercase tracking-[0.5px] md:text-[26px]">Create Account</h1>
    <p class="mb-7 text-[13px] text-muted">Track your orders and check out faster next time.</p>

    <form action="{{ route('register.attempt') }}" method="post">
      @csrf

      <label class="mb-1.5 block text-[13px] font-medium text-heading" for="name">Full name</label>
      <input class="w-full border border-line-strong bg-white px-4 py-3 text-[14px] outline-none transition-colors placeholder:text-muted focus:border-heading mb-3.5" id="name" name="name" type="text" value="{{ old('name') }}" required autofocus>
      @error('name') <p class="mb-3.5 -mt-2 text-[12px] text-salebadge">{{ $message }}</p> @enderror

      <label class="mb-1.5 block text-[13px] font-medium text-heading" for="email">Email</label>
      <input class="w-full border border-line-strong bg-white px-4 py-3 text-[14px] outline-none transition-colors placeholder:text-muted focus:border-heading mb-3.5" id="email" name="email" type="email" value="{{ old('email') }}" required>
      @error('email') <p class="mb-3.5 -mt-2 text-[12px] text-salebadge">{{ $message }}</p> @enderror

      <label class="mb-1.5 block text-[13px] font-medium text-heading" for="phone">Mobile number {{ $prefillPhone ? '' : '(optional)' }}</label>
      <input class="w-full border border-line-strong bg-white px-4 py-3 text-[14px] outline-none transition-colors placeholder:text-muted focus:border-heading mb-3.5" id="phone" name="phone" type="tel" value="{{ old('phone', $prefillPhone) }}" placeholder="10-digit mobile number" inputmode="numeric">
      @error('phone') <p class="mb-3.5 -mt-2 text-[12px] text-salebadge">{{ $message }}</p> @enderror

      <label class="mb-1.5 block text-[13px] font-medium text-heading" for="password">Password</label>
      <input class="w-full border border-line-strong bg-white px-4 py-3 text-[14px] outline-none transition-colors placeholder:text-muted focus:border-heading mb-3.5" id="password" name="password" type="password" required minlength="8">
      @error('password') <p class="mb-3.5 -mt-2 text-[12px] text-salebadge">{{ $message }}</p> @enderror

      <label class="mb-1.5 block text-[13px] font-medium text-heading" for="password_confirmation">Confirm password</label>
      <input class="w-full border border-line-strong bg-white px-4 py-3 text-[14px] outline-none transition-colors placeholder:text-muted focus:border-heading mb-5" id="password_confirmation" name="password_confirmation" type="password" required minlength="8">

      <button class="inline-flex w-full items-center justify-center gap-2 border border-black bg-black px-8 py-[13px] text-[13px] font-medium uppercase tracking-[0.5px] text-white transition-colors hover:border-accent hover:bg-accent" type="submit">
        Create Account
      </button>
    </form>

    <p class="mt-6 text-center text-[13px] text-muted">
      Already have an account? <a class="font-medium text-heading underline hover:text-accent" href="{{ route('login') }}">Login</a>
    </p>
  </div>

@endsection
