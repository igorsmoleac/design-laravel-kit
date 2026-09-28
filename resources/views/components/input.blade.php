<div class="form-group{{ $floating ? ' dlk-floating' : '' }}">
    @if ($floating)
        <input
            type="{{ $type }}"
            id="{{ $fieldId() }}"
            name="{{ $name }}"
            value="{{ $inputValue() }}"
            {{ $attributes->except('id')->class([$inputClass])->merge([
                'aria-invalid' => $hasError() ? 'true' : null,
                'aria-describedby' => $describedBy() ?: null,
                'aria-required' => $required ? 'true' : null,
                'required' => $required,
                'disabled' => $disabled,
                'readonly' => $readonly,
            ]) }}
        >
        <label for="{{ $fieldId() }}" @if ($hasValue()) class="active" @endif>{{ $labelText() }}@if ($required)
            <span class="text-danger" aria-hidden="true">*</span>
            <span class="visually-hidden">(obbligatorio)</span>
        @endif</label>
    @else
        <label class="form-label" for="{{ $fieldId() }}">{{ $labelText() }}@if ($required)
            <span class="text-danger" aria-hidden="true">*</span>
            <span class="visually-hidden">(obbligatorio)</span>
        @endif</label>
        <input
            type="{{ $type }}"
            id="{{ $fieldId() }}"
            name="{{ $name }}"
            value="{{ $inputValue() }}"
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
        <div class="invalid-feedback" id="{{ $errorId() }}" role="alert" aria-live="polite">{{ $errorMessage() }}</div>
    @endif
</div>
