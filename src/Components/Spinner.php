<?php

namespace IgorSmoleac\DesignLaravelKit\Components;

use IgorSmoleac\DesignLaravelKit\Enums\SpinnerSize;
use Illuminate\Contracts\View\View;
use InvalidArgumentException;

class Spinner extends BaseComponent
{
    protected SpinnerSize $size;

    public function __construct(
        string|SpinnerSize $size = SpinnerSize::Medium,
        public bool $double = true,
        public ?string $label = null,
        public bool $decorative = false,
    ) {
        $this->size = $size instanceof SpinnerSize
            ? $size
            : SpinnerSize::tryFrom($size) ?? throw new InvalidArgumentException("Unknown SpinnerSize: {$size}");
    }

    public function render(): View
    {
        return $this->componentView('design-laravel-kit::components.spinner');
    }

    public function size(): SpinnerSize
    {
        return $this->size;
    }

    public function cssClass(): string
    {
        return collect([
            'progress-spinner',
            'progress-spinner-active',
            $this->double ? 'progress-spinner-double' : null,
            $this->size->cssClass(),
        ])->filter()->implode(' ');
    }

    public function isDecorative(): bool
    {
        return $this->decorative;
    }
}
