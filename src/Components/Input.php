<?php

namespace IgorSmoleac\DesignLaravelKit\Components;

use IgorSmoleac\DesignLaravelKit\Components\Concerns\HandlesFieldValue;
use IgorSmoleac\DesignLaravelKit\Components\Concerns\HandlesFormField;
use Illuminate\Contracts\View\View;

class Input extends BaseFormComponent
{
    use HandlesFieldValue;
    use HandlesFormField;

    public function __construct(
        public string $name,
        public string $type = 'text',
        public ?string $label = null,
        public string|int|float|\BackedEnum|null $value = null,
        public ?string $hint = null,
        public bool $required = false,
        public bool $disabled = false,
        public bool $readonly = false,
        public bool $floating = true,
        public string $bag = 'default',
    ) {}

    public function render(): View
    {
        return $this->componentView('design-laravel-kit::components.input');
    }
}
