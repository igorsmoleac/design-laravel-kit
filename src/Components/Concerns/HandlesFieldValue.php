<?php

namespace IgorSmoleac\DesignLaravelKit\Components\Concerns;

trait HandlesFieldValue
{
    public function inputValue(): ?string
    {
        $field = $this->errorField() ?? $this->name;
        $old = session()->getOldInput($field);

        if (is_scalar($old)) {
            return $this->normalizeValue($old);
        }

        return $this->normalizeValue($this->value);
    }

    public function hasValue(): bool
    {
        return filled($this->normalizeValue($this->inputValue()));
    }
}
