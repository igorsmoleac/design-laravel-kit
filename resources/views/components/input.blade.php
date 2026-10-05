<div @class(['form-group', 'dlk-floating' => $floating, $wrapperClass => filled($wrapperClass)])>
    @if ($floating)
        <input
            type="{{ $type }}"
            id="{{ $fieldId() }}"
            name="{{ $name }}"
            @if ($type !== 'password')
                value="{{ $inputValue() }}"
            @endif
            {{ $attributes->except('id')->class([$inputClass])->merge([
                'aria-invalid' => $hasError() ? 'true' : null,
                'aria-describedby' => $describedBy() ?: null,
                'aria-required' => $required ? 'true' : null,
                'required' => $required,
                'disabled' => $disabled,
                'readonly' => $readonly,
            ]) }}
        >
        <label for="{{ $fieldId() }}" @if ($hasValue() || filled($attributes->get('placeholder'))) class="active" @endif>{{ $labelText() }}@if ($required)
            <span class="text-danger" aria-hidden="true">*</span>
            <span class="visually-hidden">{{ __('design-laravel-kit::(obbligatorio)') }}</span>
        @endif</label>
    @else
        <label class="form-label" for="{{ $fieldId() }}">{{ $labelText() }}@if ($required)
            <span class="text-danger" aria-hidden="true">*</span>
            <span class="visually-hidden">{{ __('design-laravel-kit::(obbligatorio)') }}</span>
        @endif</label>
        <input
            type="{{ $type }}"
            id="{{ $fieldId() }}"
            name="{{ $name }}"
            @if ($type !== 'password')
                value="{{ $inputValue() }}"
            @endif
            {{ $attributes->except('id')->class([$inputClass()])->merge([
                'aria-invalid' => $hasError() ? 'true' : null,
                'aria-describedby' => $describedBy() ?: null,
                'aria-required' => $required ? 'true' : null,
                'required' => $required,
                'disabled' => $disabled,
                'readonly' => $readonly,
            ]) }}
        >
    @endif
    @if (filled($hint))
        <small class="form-text" id="{{ $hintId() }}">{{ $hint }}</small>
    @endif
    @if ($hasError())
        <div class="invalid-feedback" id="{{ $errorId() }}" role="alert">{{ $errorMessage() }}</div>
    @endif
</div>
