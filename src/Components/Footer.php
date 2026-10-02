<?php

namespace IgorSmoleac\DesignLaravelKit\Components;

use Closure;
use IgorSmoleac\DesignLaravelKit\DTO\FooterConfig;
use Illuminate\Contracts\View\View;
use Illuminate\View\ComponentSlot;

class Footer extends BaseComponent
{
    protected ?FooterConfig $footerConfig = null;

    private bool $hasLegalLinkContent = false;

    public function __construct(
        public string $title = '',
        public ?string $subtitle = null,
        public ?string $logo = null,
        public ?string $logoAlt = null,
        public ?string $url = null,
        public ?string $copyright = null,
        public bool $light = false,
    ) {}

    public function render(): Closure
    {
        return function (array $data): View {
            $this->rejectArrayAttributes(['sections', 'contacts', 'social', 'social-links', 'legalLinks', 'legal-links']);
            $this->footerConfig = FooterConfig::fromArray([
                'title' => $this->title,
                'subtitle' => $this->subtitle,
                'logo' => $this->logo,
                'logoAlt' => $this->logoAlt,
                'url' => $this->url,
                'copyright' => $this->copyright,
                'light' => $this->light,
            ]);

            $legalLinks = $data['legalLinks'] ?? $data['legal-links'] ?? null;
            $slot = $data['slot'] ?? null;
            $this->hasLegalLinkContent = $this->hasSlotContent($legalLinks) || $this->hasSlotContent($slot);

            return $this->componentView('design-laravel-kit::components.footer')
                ->with($data)
                ->with(['footerConfig' => $this->footerConfig]);
        };
    }

    private function hasSlotContent(mixed $slot): bool
    {
        return $slot instanceof ComponentSlot
            ? $slot->isNotEmpty()
            : trim((string) $slot) !== '';
    }

    public function hasLegalLinks(): bool
    {
        return $this->hasLegalLinkContent;
    }

    public function hasLogo(): bool
    {
        return $this->logo !== null;
    }

    public function hasSubtitle(): bool
    {
        return $this->subtitle !== null;
    }

    public function brandUrl(): string
    {
        return $this->url ?? '#';
    }

    public function currentYear(): int
    {
        return (int) date('Y');
    }

    public function copyrightText(): string
    {
        return $this->copyright ?? '© ' . $this->currentYear() . ' ' . $this->title;
    }

    public function copyrightClass(): string
    {
        return collect(['mb-0', 'px-3', $this->hasLegalLinks() ? 'pb-4' : 'py-4'])
            ->filter()
            ->implode(' ');
    }

    public function wrapperClass(): string
    {
        return collect(['it-footer', $this->light ? 'theme-light' : null])
            ->filter()
            ->implode(' ');
    }
}
