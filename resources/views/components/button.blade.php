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
        <span class="progress-spinner progress-spinner-double progress-spinner-active size-sm" role="status" aria-hidden="true">
            <span class="progress-spinner-inner"></span>
            <span class="progress-spinner-inner"></span>
        </span>
    @endif
    {{ $slot }}
</{{ $tagName }}>
