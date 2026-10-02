<?php

namespace IgorSmoleac\DesignLaravelKit\Components;

use IgorSmoleac\DesignLaravelKit\DTO\ConfigValidator;
use Illuminate\Contracts\View\View;

class HeaderMegamenuSection extends BaseComponent
{
    public function __construct(public string $heading = '')
    {
        ConfigValidator::requiredString(['heading' => $this->heading], 'heading');
    }

    public function render(): View
    {
        return $this->componentView('design-laravel-kit::components.header-megamenu-section');
    }
}
