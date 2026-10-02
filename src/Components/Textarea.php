<?php

namespace IgorSmoleac\DesignLaravelKit\Components;

use IgorSmoleac\DesignLaravelKit\Components\Concerns\HandlesFieldValue;
use IgorSmoleac\DesignLaravelKit\Components\Concerns\HandlesFormField;
use Illuminate\Contracts\View\View;

class Textarea extends BaseFormComponent
{
    use HandlesFieldValue;
    use HandlesFormField;

    public function __construct(
        public string $name,
        public ?string $label = null,
        public ?string $value = null,
        public ?string $hint = null,
        public int $rows = 3,
        public bool $required = false,
        public bool $disabled = false,
        public bool $readonly = false,
        public bool $floating = true,
        public string $bag = 'default',
        ?string $wrapperClass = null,
    ) {
        $this->wrapperClass = $wrapperClass;
    }

    public function render(): View
    {
        return $this->componentView('design-laravel-kit::components.textarea');
    }
}
