<?php

namespace IgorSmoleac\DesignLaravelKit\Components;

use IgorSmoleac\DesignLaravelKit\Components\Concerns\HandlesFormField;
use Illuminate\Contracts\View\View;

class Input extends BaseFormComponent
{
    use HandlesFormField;

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
}
