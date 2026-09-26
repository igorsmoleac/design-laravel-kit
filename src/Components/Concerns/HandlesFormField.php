<?php

namespace IgorSmoleac\DesignLaravelKit\Components\Concerns;

use Illuminate\Support\Str;

/**
 * Shared behaviour for form field components (Input, Textarea, Select):
 * error binding, old input, labels and ARIA wiring.
 */
trait HandlesFormField
{
    public function fieldId(): string
    {
        return $this->id();
    }

    public function hasError(): bool
    {
        return ($field = $this->errorField()) !== null
            && $this->errors()->getBag($this->bag)->has($field);
    }

    public function errorMessage(): ?string
    {
        $field = $this->errorField();

        if ($field === null) {
            return null;
        }

        return $this->errors()->getBag($this->bag)->first($field) ?: null;
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

    public function inputValue(): ?string
    {
        if ($this->value !== null) {
            return $this->value;
        }

        $old = session()->getOldInput($this->errorField() ?? $this->name);

        return is_scalar($old) ? (string) $old : null;
    }

    /**
     * Checked state for checkbox/radio: old input wins when present
     * (re-population after failed validation), otherwise the checked prop.
     * A checkbox without an explicit value posts "on", so that is the
     * fallback when comparing against scalar old input.
     */
    public function isChecked(): bool
    {
        $old = session()->getOldInput($this->errorField() ?? $this->name);

        if (is_array($old)) {
            return in_array((string) $this->value, array_map(strval(...), $old), true);
        }

        if ($old !== null) {
            return (string) $old === (string) ($this->value ?? 'on');
        }

        return $this->checked;
    }

    public function hasValue(): bool
    {
        return filled($this->inputValue());
    }

    protected function idSeed(): ?string
    {
        return $this->name;
    }

    protected function errorField(): ?string
    {
        return $this->toDotNotation($this->name);
    }

    protected function hasHint(): bool
    {
        return $this->hint !== null && ! $this->hasError();
    }
}
