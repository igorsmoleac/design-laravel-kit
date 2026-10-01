<div class="form-group{{ $floating ? ' dlk-floating' : '' }}">
    @if ($floating)
        <textarea
            id="{{ $fieldId() }}"
            name="{{ $name }}"
            rows="{{ $rows }}"
            {{ $attributes->except('id')->class([$inputClass()])->merge([
                'aria-invalid' => $hasError() ? 'true' : null,
                'aria-describedby' => $describedBy() ?: null,
                'aria-required' => $required ? 'true' : null,
                'required' => $required,
                'disabled' => $disabled,
                'readonly' => $readonly,
            ]) }}
        >{{ $inputValue() }}</textarea>
        <label for="{{ $fieldId() }}" @if ($hasValue() || filled($attributes->get('placeholder'))) class="active" @endif>{{ $labelText() }}@if ($required)
            <span class="text-danger" aria-hidden="true">*</span>
            <span class="visually-hidden">{{ __('design-laravel-kit::(obbligatorio)') }}</span>
        @endif</label>
    @else
        <label class="form-label" for="{{ $fieldId() }}">{{ $labelText() }}@if ($required)
            <span class="text-danger" aria-hidden="true">*</span>
            <span class="visually-hidden">{{ __('design-laravel-kit::(obbligatorio)') }}</span>
        @endif</label>
        <textarea
            id="{{ $fieldId() }}"
            name="{{ $name }}"
            rows="{{ $rows }}"
            {{ $attributes->except('id')->class([$inputClass()])->merge([
                'aria-invalid' => $hasError() ? 'true' : null,
                'aria-describedby' => $describedBy() ?: null,
                'aria-required' => $required ? 'true' : null,
                'required' => $required,
                'disabled' => $disabled,
                'readonly' => $readonly,
            ]) }}
        >{{ $inputValue() }}</textarea>
    @endif
    @if (filled($hint))
        <small class="form-text" id="{{ $hintId() }}">{{ $hint }}</small>
    @endif
    @if ($hasError())
        <div class="invalid-feedback" id="{{ $errorId() }}" role="alert" aria-live="polite">{{ $errorMessage() }}</div>
    @endif
</div>
