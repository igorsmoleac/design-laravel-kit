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
        <label for="{{ $fieldId() }}" @if ($hasValue()) class="active" @endif>{{ $labelText() }}</label>
    @else
        <label class="form-label" for="{{ $fieldId() }}">{{ $labelText() }}</label>
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
    @if ($hasError())
        <div class="invalid-feedback" id="{{ $errorId() }}" role="alert" aria-live="polite">{{ $errorMessage() }}</div>
    @elseif (filled($hint))
        <small class="form-text" id="{{ $hintId() }}">{{ $hint }}</small>
    @endif
</div>
