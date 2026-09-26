<?php

namespace IgorSmoleac\DesignLaravelKit\Components;

use Illuminate\Contracts\View\View;

class CieButton extends BaseComponent
{
    public function __construct(
        public ?string $href = null,
        public string $size = 'm',
        public string $label = 'Entra con CIE',
    ) {}

    public function render(): View
    {
        return view('design-laravel-kit::components.cie-button');
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
