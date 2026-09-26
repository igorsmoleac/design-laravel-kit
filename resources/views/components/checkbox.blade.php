<div class="form-check">
    <input
        type="checkbox"
        id="{{ $fieldId() }}"
        name="{{ $name }}"
        @if (filled($value)) value="{{ $value }}" @endif
        {{ $attributes->except('id')->class([$inputClass()])->merge([
            'aria-invalid' => $hasError() ? 'true' : null,
            'aria-describedby' => $describedBy() ?: null,
            'aria-required' => $required ? 'true' : null,
            'required' => $required,
            'disabled' => $disabled,
            'checked' => $isChecked(),
        ]) }}
    >
    <label class="form-check-label" for="{{ $fieldId() }}">{{ $labelText() }}</label>
    @if ($hasError())
        <div class="invalid-feedback" id="{{ $errorId() }}" role="alert" aria-live="polite">{{ $errorMessage() }}</div>
    @elseif (filled($hint))
        <small class="form-text" id="{{ $hintId() }}">{{ $hint }}</small>
    @endif
</div>
