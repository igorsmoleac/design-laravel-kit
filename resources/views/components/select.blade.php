<div @class(['form-group', 'dlk-floating' => $floating, $wrapperClass => filled($wrapperClass)])>
    @if (! $floating)
        <label class="form-label" for="{{ $fieldId() }}">{{ $labelText() }}@if ($required)
            <span class="text-danger" aria-hidden="true">*</span>
            <span class="visually-hidden">{{ __('design-laravel-kit::(obbligatorio)') }}</span>
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
            <option value=""{{ $placeholderDisabled ? ' disabled' : '' }}{{ ! $hasValue() ? ' selected' : '' }}>{{ $placeholder }}</option>
        @endif
        @if ($hasSlotOptions($slot))
            {{ $slot }}
        @else
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
        @endif
    </select>
    @if ($floating)
        <label for="{{ $fieldId() }}" class="active">{{ $labelText() }}@if ($required)
            <span class="text-danger" aria-hidden="true">*</span>
            <span class="visually-hidden">{{ __('design-laravel-kit::(obbligatorio)') }}</span>
        @endif</label>
    @endif
    @if (filled($hint))
        <small class="form-text" id="{{ $hintId() }}">{{ $hint }}</small>
    @endif
    @if ($hasError())
        <div class="invalid-feedback" id="{{ $errorId() }}" role="alert">{{ $errorMessage() }}</div>
    @endif
</div>
