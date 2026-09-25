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
        <label for="{{ $fieldId() }}" @if ($hasValue()) class="active" @endif>{{ $labelText() }}</label>
    @else
        <label class="form-label" for="{{ $fieldId() }}">{{ $labelText() }}</label>
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
    @if ($hasError())
        <div class="invalid-feedback" id="{{ $errorId() }}" role="alert" aria-live="polite">{{ $errorMessage() }}</div>
    @elseif (filled($hint))
        <small class="form-text" id="{{ $hintId() }}">{{ $hint }}</small>
    @endif
</div>
