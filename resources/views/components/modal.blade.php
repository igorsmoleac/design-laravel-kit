<div
    id="{{ $dialogId() }}"
    tabindex="-1"
    role="dialog"
    aria-hidden="true"
    aria-labelledby="{{ $titleId() }}"
    @if ($hasDescription()) aria-describedby="{{ $descriptionId() }}" @endif
    {{ $attributes->except('id')->class(['modal', 'fade'])->merge([
        'data-bs-backdrop' => $static ? 'static' : null,
        'data-bs-keyboard' => $static ? 'false' : null,
    ]) }}
>
    <div class="{{ $dialogClass() }}" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title h5" id="{{ $titleId() }}">{{ $title }}</h2>
                @if ($dismissible)
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('design-laravel-kit::Chiudi finestra modale') }}"></button>
                @endif
            </div>
            <div class="modal-body" id="{{ $bodyId() }}">
                @if ($hasDescription())
                    <p id="{{ $descriptionId() }}">{{ $description }}</p>
                @endif
                {{ $slot }}
            </div>
            @isset($footer)
                <div class="modal-footer">{{ $footer }}</div>
            @endisset
        </div>
    </div>
</div>
