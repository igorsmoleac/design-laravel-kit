@aware(['select' => null])
@php($isSelected = $select !== null && $select->isSelected($value))
<option value="{{ $value }}"{{ $isSelected ? ' selected' : '' }}>{{ $label ?? $slot }}</option>
