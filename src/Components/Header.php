<?php

namespace IgorSmoleac\DesignLaravelKit\Components;

use Illuminate\Contracts\View\View;

class Header extends BaseComponent
{
    public function __construct(
        public array $slim = [],
        public array $center = [],
        public array $navbar = [],
        public bool $light = false,
        public bool $sticky = false,
        public bool $small = false,
    ) {}

    public function render(): View
    {
        return view('design-laravel-kit::components.header');
    }

    public function hasSlim(): bool
    {
        return $this->slim !== [];
    }

    public function hasCenter(): bool
    {
        return $this->center !== [];
    }

    public function hasNavbar(): bool
    {
        return $this->navbar !== [];
    }

    public function wrapperClass(): string
    {
        return collect([
            'it-header-wrapper',
            $this->sticky ? 'it-header-sticky' : null,
            $this->small ? 'it-header-small' : null,
            $this->light ? 'theme-light' : null,
        ])->filter()->implode(' ');
    }

    public function mergeSlimProps(): array
    {
        return array_merge($this->slim, [
            'light' => $this->light,
            'sticky' => false,
        ]);
    }

    public function mergeCenterProps(): array
    {
        return array_merge($this->center, [
            'light' => $this->light,
            'small' => $this->small || (bool) ($this->center['small'] ?? false),
        ]);
    }

    public function mergeNavbarProps(): array
    {
        return array_merge($this->navbar, [
            'light' => $this->light,
            'sticky' => false,
        ]);
    }
}
