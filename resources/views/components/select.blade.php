<div class="form-group{{ $floating ? ' dlk-floating' : '' }}">
    @if (! $floating)
        <label class="form-label" for="{{ $fieldId() }}">{{ $labelText() }}@if ($required)
            <span class="text-danger" aria-hidden="true">*</span>
            <span class="visually-hidden">(obbligatorio)</span>
        @endif</label>
    @endif
    <select
        id="{{ $fieldId() }}"
        name="{{ $fieldName() }}"
        {{ $attributes->except('id')->class([$inputClass()])->merge([
            'multiple' => $multiple,
            'aria-invalid' => $hasError() ? 'true' : null,
            'aria-describedby' => $describedBy() ?: null,
            'aria-required' => $required ? 'true' : null,
            'required' => $required,
            'disabled' => $disabled,
        ]) }}
    >
        @if ($placeholder)
            <option value="" disabled{{ ! $hasValue() ? ' selected' : '' }}>{{ $placeholder }}</option>
        @endif
        @foreach ($options as $optionValue => $optionLabel)
            @if (is_array($optionLabel))
                <optgroup label="{{ $optionValue }}">
                    @foreach ($optionLabel as $subValue => $subLabel)
                        <option value="{{ $subValue }}"{{ $isSelected($subValue) ? ' selected' : '' }}>{{ $subLabel }}</option>
                    @endforeach
                </optgroup>
            @else
                <option value="{{ $optionValue }}"{{ $isSelected($optionValue) ? ' selected' : '' }}>{{ $optionLabel }}</option>
            @endif
        @endforeach
    </select>
    @if ($floating)
        <label for="{{ $fieldId() }}" class="active">{{ $labelText() }}@if ($required)
            <span class="text-danger" aria-hidden="true">*</span>
            <span class="visually-hidden">(obbligatorio)</span>
        @endif</label>
    @endif
    @if (filled($hint))
        <small class="form-text" id="{{ $hintId() }}">{{ $hint }}</small>
    @endif
    @if ($hasError())
        <div class="invalid-feedback" id="{{ $errorId() }}" role="alert" aria-live="polite">{{ $errorMessage() }}</div>
    @endif
</div>
