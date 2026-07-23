@props(['block'])

<section class="py-10 md:py-[60px] bg-pinksoft">
  <div class="mx-auto w-full max-w-wrapper px-3 md:px-4 text-center">
    <h2 class="font-serif text-[24px] uppercase tracking-[3px] md:text-[34px]"><span>&mdash;</span> {{ $siteSettings['site_name'] ?? 'Estele' }} <span>&mdash;</span></h2>
    @if($block->title)
      <p class="mb-5 text-[15px] tracking-[0.5px] text-accent">&#10022; {{ $block->title }}</p>
    @endif
    @if($block->subtitle)
      <div class="mx-auto max-w-[900px] text-[13.5px] leading-[1.9] text-muted">
        <p>{{ $block->subtitle }}</p>
      </div>
    @endif
  </div>
</section>
