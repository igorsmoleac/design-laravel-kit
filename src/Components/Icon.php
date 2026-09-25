<?php

namespace IgorSmoleac\DesignLaravelKit\Components;

use IgorSmoleac\DesignLaravelKit\Enums\IconSize;
use Illuminate\Contracts\View\View;

class Icon extends BaseComponent
{
    protected IconSize $size;

    public function __construct(
        public string $name,
        string|IconSize $size = IconSize::Medium,
        public ?string $label = null,
        public ?string $spacing = null,
    ) {
        $this->size = $size instanceof IconSize ? $size : IconSize::from($size);
    }

    public function render(): View
    {
        return view('design-laravel-kit::components.icon');
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
            $this->spacing ? 'icon-' . $this->spacing : null,
        ])->filter()->unique()->implode(' ');
    }

    public function isDecorative(): bool
    {
        return $this->label === null;
    }
}
