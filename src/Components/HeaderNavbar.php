<?php

namespace IgorSmoleac\DesignLaravelKit\Components;

use Illuminate\Contracts\View\View;

class HeaderNavbar extends BaseComponent
{
    public function __construct(
        public array $items = [],
        public bool $light = false,
        public bool $sticky = false,
    ) {}

    public function render(): View
    {
        return view('design-laravel-kit::components.header-navbar');
    }

    public function hasItems(): bool
    {
        return $this->items !== [];
    }

    public function menuId(): string
    {
        return $this->id() . '-menu';
    }

    public function wrapperClass(): string
    {
        return collect([
            'it-header-navbar-wrapper',
            $this->light ? 'theme-light' : null,
        ])->filter()->implode(' ');
    }

    public function navClass(): string
    {
        return collect([
            'navbar',
            'navbar-expand-lg',
            $this->hasMegamenu() ? 'has-megamenu' : null,
        ])->filter()->implode(' ');
    }

    public function hasMegamenu(): bool
    {
        return collect($this->items)->contains(fn ($item) => filled($item['megamenu'] ?? null));
    }

    public function isItemActive(array $item): bool
    {
        return (bool) ($item['active'] ?? false);
    }
}
