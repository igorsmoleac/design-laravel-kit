<li class="list-inline-item" {{ $attributes }}>
    <a
        href="{{ $config()->url }}"
        @if ($config()->dataElement !== null) data-element="{{ $config()->dataElement }}" @endif
    >{{ $config()->text }}</a>
</li>
