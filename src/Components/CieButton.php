<?php

namespace IgorSmoleac\DesignLaravelKit\Components;

use IgorSmoleac\DesignLaravelKit\DTO\ConfigValidator;
use Illuminate\Contracts\View\View;

class CieButton extends BaseComponent
{
    public string $label;

    public function __construct(
        public ?string $href = null,
        public string $size = 'm',
        ?string $label = null,
    ) {
        ConfigValidator::optionalUrl(['href' => $this->href], 'href');
        $this->label = $label ?? __('design-laravel-kit::Entra con CIE');
    }

    public function render(): View
    {
        return $this->componentView('design-laravel-kit::components.cie-button');
    }

    public function cssClass(): string
    {
        return collect(['dlk-cie-button', 'dlk-cie-button-' . $this->size])
            ->implode(' ');
    }

    public function loginUrl(): string
    {
        return $this->href ?? '/cie/login';
    }
}
