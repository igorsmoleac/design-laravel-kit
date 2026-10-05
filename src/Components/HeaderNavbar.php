<?php

namespace IgorSmoleac\DesignLaravelKit\Components;

use Closure;
use IgorSmoleac\DesignLaravelKit\DTO\NavbarConfig;
use Illuminate\Contracts\View\View;

class HeaderNavbar extends BaseComponent
{
    protected ?NavbarConfig $navbarConfig = null;

    private bool $itemsPresent = false;

    private bool $megamenuPresent = false;

    public function __construct(
        public bool $light = false,
        public bool $sticky = false,
    ) {}

    public function render(): Closure
    {
        return function (array $data): View {
            $this->rejectArrayAttributes(['items']);
            $this->navbarConfig = NavbarConfig::fromArray([
                'light' => $this->light,
                'sticky' => $this->sticky,
            ]);

            $slotHtml = (string) ($data['slot'] ?? '');
            $this->itemsPresent = trim($slotHtml) !== '';
            $this->megamenuPresent = view()->shared('dlkHasMegamenu', false) === true;
            view()->share('dlkHasMegamenu', false);

            return $this->componentView('design-laravel-kit::components.header-navbar')
                ->with($data)
                ->with(['navbarConfig' => $this->navbarConfig]);
        };
    }

    public function hasItems(): bool
    {
        return $this->itemsPresent;
    }

    public function menuId(): string
    {
        return $this->id() . '-menu';
    }

    public function wrapperClass(): string
    {
        return collect([
            'it-header-navbar-wrapper',
            $this->light ? 'theme-light' : null,
        ])->filter()->implode(' ');
    }

    public function navClass(): string
    {
        return 'navbar navbar-expand-lg' . ($this->megamenuPresent ? ' has-megamenu' : '');
    }
}
