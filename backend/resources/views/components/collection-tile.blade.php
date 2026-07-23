@props(['category', 'route' => 'categories.show', 'objectPosition' => 'center'])

<a class="block" href="{{ route($route, $category) }}">
  <span class="block overflow-hidden bg-placeholder rounded-[8%]" style="aspect-ratio: 2/3;">
    @if($category->hasMedia('image'))
      <img class="h-full w-full object-cover transition-transform duration-700 hover:scale-105"
           style="object-position: {{ $objectPosition }};"
           src="{{ $category->getFirstMediaUrl('image', 'tile') }}"
           alt="{{ $category->name }}" loading="lazy">
    @endif
  </span>
  <p class="mt-2 text-center text-[12px] uppercase tracking-[0.3px] text-heading">{{ $category->name }}</p>
</a>
