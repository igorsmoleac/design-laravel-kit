<?php

namespace IgorSmoleac\DesignLaravelKit\Components;

use IgorSmoleac\DesignLaravelKit\Enums\ButtonSize;
use IgorSmoleac\DesignLaravelKit\Enums\ButtonVariant;
use Illuminate\Contracts\View\View;

class Button extends BaseComponent
{
    protected string $sizeClass;

    protected string $variantClass;

    public function __construct(
        string|ButtonVariant $variant = ButtonVariant::Primary,
        string|ButtonSize $size = ButtonSize::Medium,
        public string $type = 'button',
        public ?string $href = null,
        public bool $disabled = false,
        public bool $loading = false,
        public bool $block = false,
    ) {
        $this->variantClass = $variant instanceof ButtonVariant
            ? $variant->cssClass()
            : ButtonVariant::tryFrom($variant)?->cssClass() ?? 'btn-' . $variant;

        $this->sizeClass = $size instanceof ButtonSize
            ? $size->cssClass()
            : ButtonSize::tryFrom($size)?->cssClass() ?? 'btn-' . $size;
    }

    public function render(): View
    {
        return view('design-laravel-kit::components.button');
    }

    public function cssClass(): string
    {
        return collect([
            'btn',
            $this->sizeClass,
            $this->variantClass,
            $this->block ? 'btn-block' : null,
            $this->isLink() && $this->isDisabled() ? 'disabled' : null,
        ])->filter()->unique()->implode(' ');
    }

    public function isLink(): bool
    {
        return $this->href !== null;
    }

    public function isDisabled(): bool
    {
        return $this->disabled || $this->loading;
    }

    public function tagName(): string
    {
        return $this->isLink() ? 'a' : 'button';
    }
}
