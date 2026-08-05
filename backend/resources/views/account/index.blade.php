@extends('layouts.app')

@section('meta_title', 'My Account | '.($siteSettings['site_name'] ?? 'Estele'))
@section('meta_description', 'View your order history and manage your account details.')

@section('content')

  <nav class="mx-auto w-full max-w-wrapper px-3 md:px-4 flex flex-wrap items-center gap-1.5 py-4 text-[13px] text-muted" aria-label="Breadcrumb">
    <x-breadcrumb :items="[['label' => 'My Account']]" />
  </nav>

  <div class="mx-auto w-full max-w-wrapper px-3 pb-10 md:px-4 md:pb-[60px]">
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
      <h1 class="text-[20px] uppercase tracking-[0.5px] md:text-[26px]">My Account</h1>
      <form action="{{ route('logout') }}" method="post">
        @csrf
        <button class="inline-flex items-center justify-center gap-2 border border-black bg-transparent px-6 py-2.5 text-[12px] font-medium uppercase tracking-[0.5px] text-black transition-colors hover:bg-black hover:text-white" type="submit">
          Logout
        </button>
      </form>
    </div>

    <div class="grid grid-cols-1 gap-8 md:grid-cols-[1fr_320px] md:gap-[34px]">
      <section>
        <h2 class="mb-4 text-[14px] font-medium uppercase tracking-[0.4px]">Order History</h2>

        @if($orders->isEmpty())
          <p class="rounded-lg border border-line bg-pinksoft px-4 py-4 text-[13px] text-heading">
            You haven't placed any orders yet. <a class="underline hover:text-accent" href="{{ route('home') }}">Start shopping</a>.
          </p>
        @else
          <div class="space-y-4">
            @foreach($orders as $order)
              <a class="block rounded-lg border border-line p-4 transition-colors hover:border-heading" href="{{ route('checkout.confirmation', $order) }}">
                <div class="mb-2 flex flex-wrap items-center justify-between gap-2">
                  <span class="text-[13px] font-medium text-heading">Order #{{ $order->order_number }}</span>
                  <span class="inline-block rounded-full bg-pinksoft px-3 py-1 text-[11px] font-medium uppercase tracking-[0.3px] text-accent">{{ ucfirst($order->status) }}</span>
                </div>
                <p class="mb-1 text-[12px] text-muted">{{ $order->created_at->format('d M Y') }} &middot; {{ $order->items->count() }} item{{ $order->items->count() === 1 ? '' : 's' }}</p>
                <p class="text-[14px] font-medium text-price">₹{{ number_format($order->total, 0) }}</p>
              </a>
            @endforeach
          </div>
          <div class="mt-6">
            {{ $orders->links() }}
          </div>
        @endif
      </section>

      <aside class="space-y-6">
        <details class="marker-pm rounded-lg border border-line p-4" open>
          <summary class="cursor-pointer text-[13px] font-medium uppercase tracking-[0.4px] text-heading">Profile Details</summary>
          <form class="mt-4" action="{{ route('account.profile') }}" method="post">
            @csrf
            @method('PATCH')
            <label class="mb-1.5 block text-[13px] font-medium text-heading" for="name">Full name</label>
            <input class="w-full border border-line-strong bg-white px-4 py-3 text-[14px] outline-none transition-colors placeholder:text-muted focus:border-heading mb-3.5" id="name" name="name" type="text" value="{{ old('name', auth()->user()->name) }}" required>
            @error('name') <p class="mb-3.5 -mt-2 text-[12px] text-salebadge">{{ $message }}</p> @enderror

            <label class="mb-1.5 block text-[13px] font-medium text-heading" for="email">Email</label>
            <input class="w-full border border-line-strong bg-white px-4 py-3 text-[14px] outline-none transition-colors placeholder:text-muted focus:border-heading mb-3.5" id="email" name="email" type="email" value="{{ old('email', auth()->user()->email) }}" required>
            @error('email') <p class="mb-3.5 -mt-2 text-[12px] text-salebadge">{{ $message }}</p> @enderror

            <button class="inline-flex w-full items-center justify-center gap-2 border border-black bg-black px-6 py-3 text-[12px] font-medium uppercase tracking-[0.5px] text-white transition-colors hover:border-accent hover:bg-accent" type="submit">
              Save Changes
            </button>
          </form>
        </details>

        <details class="marker-pm rounded-lg border border-line p-4">
          <summary class="cursor-pointer text-[13px] font-medium uppercase tracking-[0.4px] text-heading">Change Password</summary>
          <form class="mt-4" action="{{ route('account.password') }}" method="post">
            @csrf
            @method('PATCH')
            <label class="mb-1.5 block text-[13px] font-medium text-heading" for="current_password">Current password</label>
            <input class="w-full border border-line-strong bg-white px-4 py-3 text-[14px] outline-none transition-colors placeholder:text-muted focus:border-heading mb-3.5" id="current_password" name="current_password" type="password" required>
            @error('current_password') <p class="mb-3.5 -mt-2 text-[12px] text-salebadge">{{ $message }}</p> @enderror

            <label class="mb-1.5 block text-[13px] font-medium text-heading" for="new_password">New password</label>
            <input class="w-full border border-line-strong bg-white px-4 py-3 text-[14px] outline-none transition-colors placeholder:text-muted focus:border-heading mb-3.5" id="new_password" name="password" type="password" required minlength="8">
            @error('password') <p class="mb-3.5 -mt-2 text-[12px] text-salebadge">{{ $message }}</p> @enderror

            <label class="mb-1.5 block text-[13px] font-medium text-heading" for="password_confirmation">Confirm new password</label>
            <input class="w-full border border-line-strong bg-white px-4 py-3 text-[14px] outline-none transition-colors placeholder:text-muted focus:border-heading mb-5" id="password_confirmation" name="password_confirmation" type="password" required minlength="8">

            <button class="inline-flex w-full items-center justify-center gap-2 border border-black bg-black px-6 py-3 text-[12px] font-medium uppercase tracking-[0.5px] text-white transition-colors hover:border-accent hover:bg-accent" type="submit">
              Update Password
            </button>
          </form>
        </details>
      </aside>
    </div>
  </div>

@endsection
