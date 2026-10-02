<?php

namespace IgorSmoleac\DesignLaravelKit\Components;

use IgorSmoleac\DesignLaravelKit\DTO\SocialLinkConfig;
use Illuminate\Contracts\View\View;

class HeaderSocialLink extends BaseComponent
{
    private ?SocialLinkConfig $linkConfig = null;

    public function __construct(
        public string $url = '',
        public string $label = '',
        public string $icon = 'it-link',
    ) {}

    public function render(): View
    {
        $this->linkConfig = SocialLinkConfig::fromArray(['url' => $this->url, 'label' => $this->label, 'icon' => $this->icon]);

        return $this->componentView('design-laravel-kit::components.header-social-link');
    }

    public function config(): SocialLinkConfig
    {
        return $this->linkConfig ??= SocialLinkConfig::fromArray(['url' => $this->url, 'label' => $this->label, 'icon' => $this->icon]);
    }
}
