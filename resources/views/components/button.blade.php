<{{ $tagName }}
    {{ $attributes->class([$cssClass]) }}
    @if ($isLink())
        href="{{ $href }}"
        @if ($isDisabled())
            aria-disabled="true"
            tabindex="-1"
        @endif
    @else
        type="{{ $type }}"
        @if ($isDisabled())
            disabled
            aria-disabled="true"
        @endif
    @endif
    @if ($loading)
        aria-busy="true"
    @endif
>
    @if ($loading)
        <span class="dlk-spinner-inline" role="status" aria-hidden="true"></span>
    @endif
    {{ $slot }}
</{{ $tagName }}>
