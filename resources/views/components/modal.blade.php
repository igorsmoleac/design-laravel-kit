<div
    id="{{ $dialogId() }}"
    tabindex="-1"
    role="dialog"
    aria-modal="true"
    aria-labelledby="{{ $titleId() }}"
    aria-describedby="{{ $bodyId() }}"
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
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Chiudi finestra modale"></button>
                @endif
            </div>
            <div class="modal-body" id="{{ $bodyId() }}">
                {{ $slot }}
            </div>
            @isset($footer)
                <div class="modal-footer">{{ $footer }}</div>
            @endisset
        </div>
    </div>
</div>
