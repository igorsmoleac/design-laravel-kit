<fieldset {{ $attributes->merge(['id' => $id(), 'class' => 'form-group'])->class([$wrapperClass => filled($wrapperClass)]) }}
    @if ($hasError()) aria-invalid="true" @endif
    @if (filled($hint)) aria-describedby="{{ $hintId() }}{{ $hasError() ? ' ' . $errorId() : '' }}"
    @elseif ($hasError()) aria-describedby="{{ $errorId() }}" @endif
>
    @if ($legendText())
        <legend>
            {{ $legendText() }}
            @if ($required)
                <span class="text-danger" aria-hidden="true">*</span>
                <span class="visually-hidden">{{ __('design-laravel-kit::(obbligatorio)') }}</span>
            @endif
        </legend>
    @endif

    @if (filled($hint))
        <small class="form-text" id="{{ $hintId() }}">{{ $hint }}</small>
    @endif

    {{ $slot }}

    @if ($hasError())
        <div class="invalid-feedback d-block" id="{{ $errorId() }}" role="alert">
            {{ $errorMessage() }}
        </div>
    @endif
</fieldset>
