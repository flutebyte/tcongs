@props(['title', 'subtitle' => null])

<div class="mb-5 text-center md:mb-[30px]">
  <h2 class="text-[18px] md:text-[21px] xl:text-[24px] font-medium uppercase tracking-[0.5px] text-heading">{{ $title }}</h2>
  @if($subtitle)
    <p class="mt-1 text-[13px] text-muted">{{ $subtitle }}</p>
  @endif
</div>
