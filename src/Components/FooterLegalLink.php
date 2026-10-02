<?php

namespace IgorSmoleac\DesignLaravelKit\Components;

use IgorSmoleac\DesignLaravelKit\DTO\LegalLinkConfig;
use Illuminate\Contracts\View\View;

class FooterLegalLink extends BaseComponent
{
    private ?LegalLinkConfig $linkConfig = null;

    public function __construct(
        public string $url = '',
        public string $text = '',
        public ?string $dataElement = null,
    ) {}

    public function render(): View
    {
        $this->linkConfig = LegalLinkConfig::fromArray(['url' => $this->url, 'text' => $this->text, 'dataElement' => $this->dataElement]);

        return $this->componentView('design-laravel-kit::components.footer-legal-link');
    }

    public function config(): LegalLinkConfig
    {
        return $this->linkConfig ??= LegalLinkConfig::fromArray(['url' => $this->url, 'text' => $this->text, 'dataElement' => $this->dataElement]);
    }
}
