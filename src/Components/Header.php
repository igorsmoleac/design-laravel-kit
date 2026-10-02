<?php

namespace IgorSmoleac\DesignLaravelKit\Components;

use Closure;
use IgorSmoleac\DesignLaravelKit\DTO\CenterConfig;
use IgorSmoleac\DesignLaravelKit\DTO\NavbarConfig;
use IgorSmoleac\DesignLaravelKit\DTO\SlimConfig;
use Illuminate\Contracts\View\View;

class Header extends BaseComponent
{
    protected ?SlimConfig $slimConfig = null;

    protected ?CenterConfig $centerConfig = null;

    protected ?NavbarConfig $navbarConfig = null;

    public function __construct(
        public bool $light = false,
        public bool $sticky = false,
        public bool $small = false,
    ) {}

    public function render(): Closure
    {
        return function (array $data): View {
            $this->rejectArrayAttributes(['slim', 'center', 'navbar']);

            $slim = $this->slotAttributes($data, 'slim');
            $center = $this->slotAttributes($data, 'center');
            $navbar = $this->slotAttributes($data, 'navbar');

            $this->slimConfig = $slim === null ? null : SlimConfig::fromArray($slim);
            $this->centerConfig = $center === null ? null : CenterConfig::fromArray($center);
            $this->navbarConfig = $navbar === null ? null : NavbarConfig::fromArray($navbar);

            return $this->componentView('design-laravel-kit::components.header')
                ->with($data)
                ->with([
                    'slimConfig' => $this->slimConfig,
                    'centerConfig' => $this->centerConfig,
                    'navbarConfig' => $this->navbarConfig,
                ]);
        };
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
}
