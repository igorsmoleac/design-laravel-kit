<div role="alert" {{ $attributes->class([$cssClass()]) }}>
    @if ($icon)
        <x-italia::icon :name="$iconName()" />
    @endif
    @if ($title)
        <h4 class="alert-heading">{{ $title }}</h4>
    @endif
    <div>{{ $slot }}</div>
    @if ($dismissible)
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="{{ __('design-laravel-kit::Chiudi') }}"></button>
    @endif
</div>
