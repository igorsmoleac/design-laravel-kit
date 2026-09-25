<svg
    {{ $attributes->class([$svgClass]) }}
    @if ($isDecorative())
        aria-hidden="true"
    @else
        role="img"
        aria-label="{{ $label }}"
    @endif
>
    <use href="{{ $spriteUrl }}#{{ $iconName }}"></use>
</svg>
