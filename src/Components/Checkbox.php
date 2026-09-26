<?php

namespace IgorSmoleac\DesignLaravelKit\Components;

use IgorSmoleac\DesignLaravelKit\Components\Concerns\HandlesFormField;
use Illuminate\Contracts\View\View;

class Checkbox extends BaseComponent
{
    use HandlesFormField;

    public function __construct(
        public string $name,
        public ?string $value = null,
        public ?string $label = null,
        public bool $checked = false,
        public bool $disabled = false,
        public bool $required = false,
        public ?string $hint = null,
        public string $bag = 'default',
    ) {}

    public function render(): View
    {
        return view('design-laravel-kit::components.checkbox');
    }

    public function inputClass(): string
    {
        return collect(['form-check-input', $this->hasError() ? 'is-invalid' : null])
            ->filter()
            ->implode(' ');
    }
}
