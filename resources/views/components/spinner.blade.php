<div {{ $attributes->class([$cssClass()])->merge([
    'role' => $isDecorative() ? null : 'status',
    'aria-label' => $label,
    'aria-hidden' => $isDecorative() ? 'true' : null,
]) }}>
    @if ($double)
        <div class="progress-spinner-inner"></div>
        <div class="progress-spinner-inner"></div>
    @endif
    @if (! $isDecorative())
        <span class="visually-hidden">{{ $label }}</span>
    @endif
</div>
