<?php

namespace IgorSmoleac\DesignLaravelKit\Components;

use Illuminate\Contracts\View\View;

class SelectOption extends BaseComponent
{
    public function __construct(
        public string|int|float|null $value,
        public ?string $label = null,
    ) {}

    public function render(): View
    {
        return $this->componentView('design-laravel-kit::components.select-option');
    }
}
