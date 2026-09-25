<?php

namespace IgorSmoleac\DesignLaravelKit\Components;

use Illuminate\Contracts\View\View;

class HeaderSlim extends BaseComponent
{
    public function __construct(
        public string $ente = 'Ente appartenenza',
        public ?string $enteUrl = null,
        public array $links = [],
        public array $languages = [],
        public ?string $loginUrl = null,
        public string $loginLabel = 'Accedi',
        public bool $light = false,
        public bool $sticky = false,
    ) {}

    public function render(): View
    {
        return view('design-laravel-kit::components.header-slim');
    }

    public function hasEnteLink(): bool
    {
        return $this->enteUrl !== null;
    }

    public function hasLinks(): bool
    {
        return $this->links !== [];
    }

    public function hasLanguages(): bool
    {
        return $this->languages !== [];
    }

    public function hasLogin(): bool
    {
        return $this->loginUrl !== null;
    }

    public function menuId(): string
    {
        return $this->id() . '-mobile-menu';
    }

    public function wrapperClass(): string
    {
        return collect(['it-header-slim-wrapper', $this->light ? 'theme-light' : null])
            ->filter()
            ->implode(' ');
    }

    public function currentLanguageLabel(): string
    {
        $active = collect($this->languages)->first(fn ($language) => $language['active'] ?? false);

        return $active['label'] ?? $this->languages[0]['label'] ?? '';
    }

    public function isLinkActive(array $link): bool
    {
        return (bool) ($link['active'] ?? false);
    }

    public function isLanguageActive(array $language): bool
    {
        return (bool) ($language['active'] ?? false);
    }
}
