<?php

namespace IgorSmoleac\DesignLaravelKit\Components;

use IgorSmoleac\DesignLaravelKit\Enums\IconSize;
use IgorSmoleac\DesignLaravelKit\Enums\IconSpacing;
use Illuminate\Contracts\View\View;
use InvalidArgumentException;

class Icon extends BaseComponent
{
    protected IconSize $size;

    protected ?IconSpacing $spacing = null;

    public function __construct(
        public string $name,
        string|IconSize $size = IconSize::Medium,
        public ?string $label = null,
        string|IconSpacing|null $spacing = null,
    ) {
        $this->size = $size instanceof IconSize
            ? $size
            : IconSize::tryFrom($size) ?? throw new InvalidArgumentException("Unknown IconSize: {$size}");

        $this->spacing = $spacing === null
            ? null
            : ($spacing instanceof IconSpacing
                ? $spacing
                : IconSpacing::tryFrom($spacing) ?? throw new InvalidArgumentException("Unknown IconSpacing: {$spacing}"));
    }

    public function render(): View
    {
        return $this->componentView('design-laravel-kit::components.icon');
    }

    public function size(): IconSize
    {
        return $this->size;
    }

    public function spacing(): ?IconSpacing
    {
        return $this->spacing;
    }

    public function iconName(): string
    {
        return str_starts_with($this->name, 'it-') ? $this->name : 'it-' . $this->name;
    }

    public function spriteUrl(): string
    {
        return asset(config('design-laravel-kit.assets_path') . '/svg/sprites.svg');
    }

    public function svgClass(): string
    {
        return collect([
            'icon',
            $this->size->cssClass(),
            $this->spacing?->cssClass(),
        ])->filter()->unique()->implode(' ');
    }

    public function isDecorative(): bool
    {
        return $this->label === null;
    }
}
