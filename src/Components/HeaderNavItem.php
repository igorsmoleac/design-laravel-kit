<?php

namespace IgorSmoleac\DesignLaravelKit\Components;

use IgorSmoleac\DesignLaravelKit\DTO\NavItemConfig;
use Illuminate\Contracts\View\View;

class HeaderNavItem extends BaseComponent
{
    private ?NavItemConfig $itemConfig = null;

    public function __construct(
        public string $text = '',
        public string $url = '',
        public bool $active = false,
        public bool $dropdown = false,
    ) {}

    public function render(): View
    {
        $this->itemConfig = NavItemConfig::fromArray(['text' => $this->text, 'url' => $this->url, 'active' => $this->active]);

        return $this->componentView('design-laravel-kit::components.header-nav-item');
    }

    public function config(): NavItemConfig
    {
        return $this->itemConfig ??= NavItemConfig::fromArray(['text' => $this->text, 'url' => $this->url, 'active' => $this->active]);
    }
}
