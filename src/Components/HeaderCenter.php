<?php

namespace IgorSmoleac\DesignLaravelKit\Components;

use Illuminate\Contracts\View\View;

class HeaderCenter extends BaseComponent
{
    public function __construct(
        public string $title,
        public ?string $tagline = null,
        public ?string $logo = null,
        public ?string $logoAlt = null,
        public ?string $url = null,
        public array $socialLinks = [],
        public ?string $searchUrl = null,
        public bool $small = false,
        public bool $light = false,
    ) {}

    public function render(): View
    {
        return view('design-laravel-kit::components.header-center');
    }

    public function hasTagline(): bool
    {
        return $this->tagline !== null;
    }

    public function hasLogo(): bool
    {
        return $this->logo !== null;
    }

    public function hasSocialLinks(): bool
    {
        return $this->socialLinks !== [];
    }

    public function hasSearch(): bool
    {
        return $this->searchUrl !== null;
    }

    public function brandUrl(): string
    {
        return $this->url ?? '#';
    }

    public function wrapperClass(): string
    {
        return collect([
            'it-header-center-wrapper',
            $this->small ? 'it-small-header' : null,
            $this->light ? 'theme-light' : null,
        ])->filter()->implode(' ');
    }
}
