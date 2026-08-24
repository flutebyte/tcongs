@extends('layouts.app')

@section('meta_title', 'Forgot Password | '.($siteSettings['site_name'] ?? 'Estele'))
@section('meta_description', 'Reset the password for your account.')

@section('content')

<div class="bg-ivory py-10 md:py-16 min-h-[calc(100vh-220px)]">
  <div class="mx-auto w-full max-w-[450px] px-4">
    <div class="rounded-2xl border border-line bg-white p-6 sm:p-8 shadow-md">

      <h1 class="font-serif text-[24px] font-semibold text-heading text-center mb-1">Forgot Password?</h1>
      <p class="text-[13px] text-muted text-center mb-6">Enter your email and we&rsquo;ll send you a link to reset it.</p>

      <form action="{{ route('password.email') }}" method="post" class="space-y-4">
        @csrf

        <div>
          <label class="mb-1.5 block text-[13px] font-medium text-heading" for="email">Email Address</label>
          <input class="h-12 w-full rounded-lg border border-line-strong bg-white px-4 text-[14px] outline-none transition-colors placeholder:text-muted focus:border-accent focus:ring-1 focus:ring-accent" id="email" name="email" type="email" value="{{ old('email') }}" placeholder="name@example.com" required autofocus>
          @error('email') <p class="mt-1 text-[12px] text-salebadge">{{ $message }}</p> @enderror
        </div>

        <button class="mt-2 h-12 w-full rounded-lg bg-accent text-[13px] font-semibold uppercase tracking-[0.6px] text-white shadow-sm transition-colors hover:bg-accent-dark" type="submit">
          Send Reset Link
        </button>
      </form>

      <p class="mt-6 text-center text-[13px] text-muted">
        Remembered it? <a class="font-semibold text-accent underline hover:text-accent-dark" href="{{ route('login') }}">Back to login</a>
      </p>

    </div>
  </div>
</div>

@endsection
