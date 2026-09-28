<div {{ $attributes->class([$cssClass()])->merge([
    'role' => $isDecorative() ? null : 'status',
    'aria-label' => $isDecorative() ? null : ($label ?? 'Caricamento in corso'),
    'aria-hidden' => $isDecorative() ? 'true' : null,
]) }}>
    @if ($double)
        <div class="progress-spinner-inner"></div>
        <div class="progress-spinner-inner"></div>
    @endif
    @if (! $isDecorative())
        <span class="visually-hidden">{{ $label ?? 'Caricamento in corso' }}</span>
    @endif
</div>
