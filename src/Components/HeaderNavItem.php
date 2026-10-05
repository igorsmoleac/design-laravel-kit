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
        $this->itemConfig = $this->config();

        return $this->componentView('design-laravel-kit::components.header-nav-item');
    }

    public function config(): NavItemConfig
    {
        if ($this->itemConfig !== null) {
            return $this->itemConfig;
        }

        $url = $this->dropdown && $this->url === '' ? '#' : $this->url;

        return $this->itemConfig = NavItemConfig::fromArray(['text' => $this->text, 'url' => $url, 'active' => $this->active]);
    }
}
