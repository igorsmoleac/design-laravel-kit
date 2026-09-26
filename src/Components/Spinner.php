<?php

namespace IgorSmoleac\DesignLaravelKit\Components;

use Illuminate\Contracts\View\View;

class Spinner extends BaseComponent
{
    public function __construct(
        public string $size = 'md',
        public bool $double = true,
        public ?string $label = null,
    ) {}

    public function render(): View
    {
        return view('design-laravel-kit::components.spinner');
    }

    public function cssClass(): string
    {
        return collect([
            'progress-spinner',
            'progress-spinner-active',
            $this->double ? 'progress-spinner-double' : null,
            'size-' . $this->size,
        ])->filter()->implode(' ');
    }

    public function isDecorative(): bool
    {
        return $this->label === null;
    }
}
