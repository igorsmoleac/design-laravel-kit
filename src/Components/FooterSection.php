<?php

namespace IgorSmoleac\DesignLaravelKit\Components;

use IgorSmoleac\DesignLaravelKit\DTO\ConfigValidator;
use Illuminate\Contracts\View\View;

class FooterSection extends BaseComponent
{
    public function __construct(
        public string $title,
        public ?string $url = null,
    ) {
        ConfigValidator::requiredString(['title' => $this->title], 'title');
        ConfigValidator::assertUrl($this->url, 'url');
    }

    public function render(): View
    {
        return $this->componentView('design-laravel-kit::components.footer-section');
    }
}
