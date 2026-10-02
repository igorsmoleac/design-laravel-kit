<?php

namespace IgorSmoleac\DesignLaravelKit\Components;

use IgorSmoleac\DesignLaravelKit\Components\Concerns\HandlesCheckableField;
use IgorSmoleac\DesignLaravelKit\Components\Concerns\HandlesFormField;
use Illuminate\Contracts\View\View;

class Radio extends BaseFormComponent
{
    use HandlesCheckableField;
    use HandlesFormField;

    public function __construct(
        public string $name,
        public string|int|float|\BackedEnum|null $value = null,
        public ?string $label = null,
        public bool $checked = false,
        public bool $disabled = false,
        public bool $required = false,
        public ?string $hint = null,
        public string $bag = 'default',
        ?string $wrapperClass = null,
    ) {
        $this->wrapperClass = $wrapperClass;
    }

    public function render(): View
    {
        return $this->componentView('design-laravel-kit::components.radio');
    }

    public function inputClass(): string
    {
        return collect(['form-check-input', $this->hasError() ? 'is-invalid' : null])
            ->filter()
            ->implode(' ');
    }
}
