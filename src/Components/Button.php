<?php

namespace IgorSmoleac\DesignLaravelKit\Components;

use IgorSmoleac\DesignLaravelKit\DTO\ConfigValidator;
use IgorSmoleac\DesignLaravelKit\Enums\ButtonSize;
use IgorSmoleac\DesignLaravelKit\Enums\ButtonVariant;
use Illuminate\Contracts\View\View;
use InvalidArgumentException;

class Button extends BaseComponent
{
    protected ButtonVariant $variant;

    protected ButtonSize $size;

    public function __construct(
        string|ButtonVariant $variant = ButtonVariant::Primary,
        string|ButtonSize $size = ButtonSize::Medium,
        public string $type = 'button',
        public ?string $href = null,
        public bool $disabled = false,
        public bool $loading = false,
        public bool $block = false,
    ) {
        ConfigValidator::optionalUrl(['href' => $this->href], 'href');

        $this->variant = $variant instanceof ButtonVariant
            ? $variant
            : ButtonVariant::tryFrom($variant) ?? throw new InvalidArgumentException("Unknown ButtonVariant: {$variant}");

        $this->size = $size instanceof ButtonSize
            ? $size
            : ButtonSize::tryFrom($size) ?? throw new InvalidArgumentException("Unknown ButtonSize: {$size}");
    }

    public function render(): View
    {
        return $this->componentView('design-laravel-kit::components.button');
    }

    public function variant(): ButtonVariant
    {
        return $this->variant;
    }

    public function size(): ButtonSize
    {
        return $this->size;
    }

    public function cssClass(): string
    {
        return collect([
            'btn',
            $this->size->cssClass(),
            $this->variant->cssClass(),
            $this->block ? 'w-100' : null,
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
