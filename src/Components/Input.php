<?php

namespace IgorSmoleac\DesignLaravelKit\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;

class Input extends BaseComponent
{
    public function __construct(
        public string $name,
        public string $type = 'text',
        public ?string $label = null,
        public ?string $value = null,
        public ?string $hint = null,
        public bool $required = false,
        public bool $disabled = false,
        public bool $readonly = false,
        public bool $floating = true,
        public string $bag = 'default',
    ) {}

    public function render(): View
    {
        return view('design-laravel-kit::components.input');
    }

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
