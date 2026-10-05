<?php

namespace IgorSmoleac\DesignLaravelKit\Components;

use Closure;
use IgorSmoleac\DesignLaravelKit\DTO\CenterConfig;
use IgorSmoleac\DesignLaravelKit\DTO\FooterConfig;
use IgorSmoleac\DesignLaravelKit\DTO\NavbarConfig;
use IgorSmoleac\DesignLaravelKit\DTO\SlimConfig;
use Illuminate\Contracts\View\View;

class Layout extends BaseComponent
{
    public string $skipLabel;

    protected ?SlimConfig $slimConfig = null;

    protected ?CenterConfig $centerConfig = null;

    protected ?NavbarConfig $navbarConfig = null;

    protected ?FooterConfig $footerConfig = null;

    public function __construct(
        public string $title = '',
        public ?string $description = null,
        public ?string $lang = null,
        public bool $light = false,
        public bool $sticky = false,
        public bool $skipToContent = true,
        ?string $skipLabel = null,
        public string $bodyClass = '',
        public ?string $mainClass = null,
        public ?string $footerTitle = null,
        public ?string $footerSubtitle = null,
        public ?string $footerLogo = null,
        public ?string $footerLogoAlt = null,
        public ?string $footerUrl = null,
        public ?string $footerCopyright = null,
    ) {
        $this->skipLabel = $skipLabel ?? __('design-laravel-kit::Vai al contenuto principale');
    }

    public function render(): Closure
    {
        return function (array $data): View {
            $this->rejectArrayAttributes(['slim', 'center', 'navbar', 'footer']);

            $slim = $this->slotAttributes($data, 'slim');
            $center = $this->slotAttributes($data, 'center');
            $navbar = $this->slotAttributes($data, 'navbar');
            $footer = $this->slotAttributes($data, 'footer');

            $this->slimConfig = $slim === null ? null : SlimConfig::fromArray($slim);
            $this->centerConfig = $center === null ? null : CenterConfig::fromArray($center);
            $this->navbarConfig = $navbar === null ? null : NavbarConfig::fromArray($navbar);
            $footerDefaults = [
                'title' => $this->footerTitle ?? (string) config('app.name'),
                'subtitle' => $this->footerSubtitle,
                'logo' => $this->footerLogo,
                'logoAlt' => $this->footerLogoAlt,
                'url' => $this->footerUrl,
                'copyright' => $this->footerCopyright,
            ];
            $footerAttributes = array_merge($footerDefaults, $footer ?? []);
            $this->footerConfig = FooterConfig::fromArray($footerAttributes);
            $footerSections = $data['footerSections'] ?? $data['footer-sections'] ?? null;
            $footerContacts = $data['footerContacts'] ?? $data['footer-contacts'] ?? null;
            $footerSocial = $data['footerSocial'] ?? $data['footer-social'] ?? null;
            $footerLegalLinks = $data['footerLegalLinks'] ?? $data['footer-legal-links'] ?? $data['footer'] ?? null;

            return $this->componentView('design-laravel-kit::components.layout')
                ->with($data)
                ->with([
                    'slimConfig' => $this->slimConfig,
                    'centerConfig' => $this->centerConfig,
                    'navbarConfig' => $this->navbarConfig,
                    'footerConfig' => $this->footerConfig,
                    'footerSections' => $footerSections,
                    'footerContacts' => $footerContacts,
                    'footerSocial' => $footerSocial,
                    'footerLegalLinks' => $footerLegalLinks,
                ]);
        };
    }

    public function pageTitle(): string
    {
        return filled($this->title) ? $this->title : (string) config('app.name');
    }

    public function htmlLang(): string
    {
        return $this->lang ?? app()->getLocale();
    }

    public function hasDescription(): bool
    {
        return filled($this->description);
    }

    public function bodyClasses(): string
    {
        return collect(['bg-white', $this->bodyClass])
            ->filter()
            ->implode(' ');
    }
}
