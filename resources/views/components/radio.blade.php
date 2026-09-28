@aware(['grouped' => false])

<div class="form-check">
    <input
        type="radio"
        id="{{ $fieldId() }}"
        name="{{ $name }}"
        @if (filled($value)) value="{{ $value }}" @endif
        {{ $attributes->except('id')->class([$inputClass()])->merge([
            'aria-invalid' => $hasError() ? 'true' : null,
            'aria-describedby' => $grouped ? (filled($hint) ? $hintId() : null) : ($describedBy() ?: null),
            'aria-required' => $required ? 'true' : null,
            'required' => $required,
            'disabled' => $disabled,
            'checked' => $isChecked(),
        ]) }}
    >
    <label class="form-check-label" for="{{ $fieldId() }}">{{ $labelText() }}@if ($required)
        <span class="text-danger" aria-hidden="true">*</span>
        <span class="visually-hidden">(obbligatorio)</span>
    @endif</label>
    @if (filled($hint))
        <small class="form-text" id="{{ $hintId() }}">{{ $hint }}</small>
    @endif
    @if ($hasError() && ! $grouped)
        <div class="invalid-feedback" id="{{ $errorId() }}" role="alert" aria-live="polite">{{ $errorMessage() }}</div>
    @endif
</div>
