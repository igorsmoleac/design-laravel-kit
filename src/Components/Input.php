<?php

namespace IgorSmoleac\DesignLaravelKit\Components;

use IgorSmoleac\DesignLaravelKit\Components\Concerns\HandlesFieldValue;
use IgorSmoleac\DesignLaravelKit\Components\Concerns\HandlesFormField;
use Illuminate\Contracts\View\View;

class Input extends BaseFormComponent
{
    use HandlesFieldValue {
        inputValue as parentInputValue;
    }
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
        ?string $wrapperClass = null,
    ) {
        $this->wrapperClass = $wrapperClass;
    }

    public function inputValue(): ?string
    {
        if ($this->type === 'password') {
            return null;
        }

        return $this->parentInputValue();
    }

    public function render(): View
    {
        return $this->componentView('design-laravel-kit::components.input');
    }
}
