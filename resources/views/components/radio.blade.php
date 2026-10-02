@aware(['grouped' => false, 'groupErrorId' => null])

@php
    $describedByIds = $grouped
        ? collect([$groupErrorId, filled($hint) ? $hintId() : null])->filter()->implode(' ')
        : ($describedBy() ?: null);
@endphp

<div @class(['form-check', $wrapperClass => filled($wrapperClass)])>
    <input
        type="radio"
        id="{{ $fieldId() }}"
        name="{{ $name }}"
        @if (filled($normalizeValue($value))) value="{{ $normalizeValue($value) }}" @endif
        {{ $attributes->except('id')->class([$inputClass()])->merge([
            'aria-invalid' => $hasError() ? 'true' : null,
            'aria-describedby' => $describedByIds,
            'aria-required' => $required ? 'true' : null,
            'required' => $required,
            'disabled' => $disabled,
            'checked' => $isChecked(),
        ]) }}
    >
    <label class="form-check-label" for="{{ $fieldId() }}">{{ $labelText() }}@if ($required)
        <span class="text-danger" aria-hidden="true">*</span>
        <span class="visually-hidden">{{ __('design-laravel-kit::(obbligatorio)') }}</span>
    @endif</label>
    @if (filled($hint))
        <small class="form-text" id="{{ $hintId() }}">{{ $hint }}</small>
    @endif
    @if ($hasError() && ! $grouped)
        <div class="invalid-feedback" id="{{ $errorId() }}" role="alert">{{ $errorMessage() }}</div>
    @endif
</div>
