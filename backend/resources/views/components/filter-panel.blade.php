{{--
  Shared filter panel for /search results and category pages (Phase 5/8).
  Layout modeled on estele.co's own "Filter" toggle (collapsed by default,
  expands to price/availability/category facets + an Apply button), adapted
  to the fields this schema actually tracks — no invented stone-type/color
  facets since Product/Category don't carry those.

  All filtering is a plain GET form (no JS required to work) so it degrades
  gracefully and matches every other filter/sort control already in this
  codebase (see categories/show.blade.php's sort <select>).
--}}
@props([
  'action',
  'q' => null,
  'sort' => null,
  'minPrice' => null,
  'maxPrice' => null,
  'inStock' => false,
  'categories' => null,
  'selectedCategories' => [],
])

<details class="mb-5 rounded-lg border border-line" data-filter-panel {{ ($minPrice || $maxPrice || $inStock || count($selectedCategories)) ? 'open' : '' }}>
  <summary class="flex cursor-pointer select-none items-center gap-2 px-4 py-3 text-[13px] font-medium uppercase tracking-[0.4px] text-heading">
    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 6h16M7 12h10M10 18h4"/></svg>
    Filter
  </summary>
  <form class="grid grid-cols-1 gap-5 border-t border-line p-4 sm:grid-cols-2 md:grid-cols-3" method="get" action="{{ $action }}">
    @if($q !== null)
      <input type="hidden" name="q" value="{{ $q }}">
    @endif
    @if($sort !== null)
      <input type="hidden" name="sort" value="{{ $sort }}">
    @endif

    <div>
      <p class="mb-2 text-[12px] font-medium uppercase tracking-[0.4px] text-heading">Price</p>
      <div class="flex items-center gap-2">
        <label class="sr-only-custom" for="filter-min-price">Minimum price</label>
        <input class="w-full border border-line-strong bg-white px-3 py-2 text-[13px] outline-none transition-colors focus:border-heading" id="filter-min-price" type="number" name="min_price" value="{{ $minPrice }}" placeholder="Min" min="0">
        <span class="text-muted">&ndash;</span>
        <label class="sr-only-custom" for="filter-max-price">Maximum price</label>
        <input class="w-full border border-line-strong bg-white px-3 py-2 text-[13px] outline-none transition-colors focus:border-heading" id="filter-max-price" type="number" name="max_price" value="{{ $maxPrice }}" placeholder="Max" min="0">
      </div>
    </div>

    <div>
      <p class="mb-2 text-[12px] font-medium uppercase tracking-[0.4px] text-heading">Availability</p>
      <label class="flex items-center gap-2 text-[13px] text-heading">
        <input type="checkbox" name="in_stock" value="1" @checked($inStock)>
        In stock only
      </label>
    </div>

    @if($categories && $categories->isNotEmpty())
      <div>
        <p class="mb-2 text-[12px] font-medium uppercase tracking-[0.4px] text-heading">Category</p>
        <div class="flex max-h-[140px] flex-col gap-1.5 overflow-y-auto">
          @foreach($categories as $cat)
            <label class="flex items-center gap-2 text-[13px] text-heading">
              <input type="checkbox" name="category[]" value="{{ $cat->slug }}" @checked(in_array($cat->slug, $selectedCategories, true))>
              {{ $cat->name }}
            </label>
          @endforeach
        </div>
      </div>
    @endif

    <div class="flex items-center gap-4 sm:col-span-2 md:col-span-3">
      <button class="inline-flex items-center justify-center gap-2 border border-black bg-black px-6 py-2.5 text-[12px] font-medium uppercase tracking-[0.5px] text-white transition-colors hover:border-accent hover:bg-accent" type="submit">
        Apply Filters
      </button>
      <a class="text-[12px] text-muted underline hover:text-accent" href="{{ $action }}{{ $q !== null ? '?q='.urlencode($q) : '' }}">Clear filters</a>
    </div>
  </form>
</details>
