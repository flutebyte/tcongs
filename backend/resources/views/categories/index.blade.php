@extends('layouts.app')

@section('meta_title', 'Categories | '.($siteSettings['site_name'] ?? 'Estele'))

@section('content')

  <div class="mx-auto w-full max-w-wrapper px-3 py-6 md:px-4">
    <x-breadcrumb :items="[['label' => 'Categories']]" />

    <div class="mb-6 text-center">
      <h1 class="text-[20px] uppercase tracking-[0.5px] md:text-[26px] mb-2.5">All Categories</h1>
    </div>

    <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 md:gap-5 xl:grid-cols-5">
      @foreach($categories as $category)
        <x-collection-tile :category="$category" />
      @endforeach
    </div>
  </div>

@endsection
