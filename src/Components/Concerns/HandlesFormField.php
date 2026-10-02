<?php

namespace IgorSmoleac\DesignLaravelKit\Components\Concerns;

use Illuminate\Support\Str;

/**
 * Shared behaviour for form field components (Input, Textarea, Select):
 * error binding, old input, labels and ARIA wiring.
 */
trait HandlesFormField
{
    protected function baseFieldName(): string
    {
        return $this->name;
    }

    protected function baseFieldHint(): ?string
    {
        return $this->hint;
    }

    public function fieldId(): string
    {
        return $this->id();
    }

    public function inputClass(): string
    {
        return collect(['form-control', $this->hasError() ? 'is-invalid' : null])
            ->filter()
            ->implode(' ');
    }

    public function labelText(): string
    {
        return $this->label ?? Str::headline(str_replace(['[', ']'], ' ', $this->name));
    }

    public function normalizeValue(mixed $value): ?string
    {
        if ($value instanceof \BackedEnum) {
            return (string) $value->value;
        }

        return is_scalar($value) ? (string) $value : null;
    }
}
