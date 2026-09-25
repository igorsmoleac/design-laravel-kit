<?php

namespace IgorSmoleac\DesignLaravelKit\Components;

use Illuminate\Contracts\View\View;

class Footer extends BaseComponent
{
    public function __construct(
        public string $title,
        public ?string $subtitle = null,
        public ?string $logo = null,
        public ?string $logoAlt = null,
        public ?string $url = null,
        public array $sections = [],
        public array $contacts = [],
        public array $socialLinks = [],
        public array $legalLinks = [],
        public ?string $copyright = null,
        public bool $light = false,
    ) {}

    public function render(): View
    {
        return view('design-laravel-kit::components.footer');
    }

    public function hasSections(): bool
    {
        return $this->sections !== [];
    }

    public function hasContacts(): bool
    {
        return $this->contacts !== [];
    }

    public function hasSocialLinks(): bool
    {
        return $this->socialLinks !== [];
    }

    public function hasLegalLinks(): bool
    {
        return $this->legalLinks !== [];
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
        return collect([
            'mb-0',
            'px-3',
            $this->hasLegalLinks() ? 'pb-4' : 'py-4',
        ])->filter()->implode(' ');
    }

    public function wrapperClass(): string
    {
        return collect(['it-footer', $this->light ? 'theme-light' : null])
            ->filter()
            ->implode(' ');
    }

    public function sectionUrl(array $section): ?string
    {
        $url = $section['url'] ?? null;

        return filled($url) ? (string) $url : null;
    }

    public function isAddress(array $contact): bool
    {
        return ($contact['type'] ?? '') === 'address';
    }

    public function linkableContacts(): array
    {
        return array_values(array_filter($this->contacts, fn ($contact) => ! $this->isAddress($contact)));
    }

    public function contactIcon(array $contact): string
    {
        return match ($contact['type'] ?? '') {
            'phone' => 'it-telephone',
            'email', 'pec' => 'it-mail',
            default => 'it-link',
        };
    }

    public function contactHref(array $contact): string
    {
        $value = (string) ($contact['value'] ?? '');

        return match ($contact['type'] ?? '') {
            'phone' => 'tel:' . preg_replace('/[^+\d]/', '', $value),
            'email', 'pec' => 'mailto:' . $value,
            default => '#',
        };
    }

    public function contactLabel(array $contact): string
    {
        $label = $contact['label'] ?? null;

        return filled($label) ? $label . ': ' . ($contact['value'] ?? '') : (string) ($contact['value'] ?? '');
    }
}
