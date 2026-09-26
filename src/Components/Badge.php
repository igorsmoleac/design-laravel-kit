<?php

namespace IgorSmoleac\DesignLaravelKit\Components;

use Illuminate\Contracts\View\View;

class Badge extends BaseComponent
{
    public function __construct(
        public string $variant = 'primary',
        public bool $pill = false,
        public ?string $href = null,
    ) {}

    public function render(): View
    {
        return view('design-laravel-kit::components.badge');
    }

    public function cssClass(): string
    {
        return collect([
            'badge',
            $this->pill ? 'rounded-pill' : null,
            'bg-' . $this->variant,
        ])->filter()->implode(' ');
    }

    public function tagName(): string
    {
        return $this->href !== null ? 'a' : 'span';
    }
}
