<li class="list-inline-item" {{ $attributes }}>
    <a class="p-2 text-white" href="{{ $config()->url }}" aria-label="{{ $config()->label }}" target="_blank" rel="noopener noreferrer">
        <x-italia::icon :name="$config()->icon" size="sm" class="icon-white align-top" />
    </a>
</li>
