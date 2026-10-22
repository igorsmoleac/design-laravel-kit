<?php

namespace IgorSmoleac\DesignLaravelKit\Components\Concerns;

trait HandlesCheckableField
{
    /**
     * Checked state for checkbox/radio: old input wins when the field key
     * is present (re-population after failed validation), otherwise the
     * checked prop. A checkbox without an explicit value posts "on", so
     * that is the fallback when comparing against scalar old input.
     */
    public function isChecked(): bool
    {
        $old = session()->getOldInput($this->errorField() ?? $this->name);

        if (is_array($old)) {
            return in_array($this->normalizeValue($this->value) ?? '', array_map(fn ($value) => $this->normalizeValue($value) ?? '', $old), true);
        }

        if ($old !== null) {
            return $this->normalizeValue($old) === ($this->normalizeValue($this->value) ?? 'on');
        }

        return $this->checked;
    }
}
