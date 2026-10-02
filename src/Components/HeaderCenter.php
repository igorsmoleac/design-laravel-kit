<?php

namespace IgorSmoleac\DesignLaravelKit\Components;

use Closure;
use IgorSmoleac\DesignLaravelKit\DTO\CenterConfig;
use Illuminate\Contracts\View\View;
use Illuminate\View\ComponentSlot;

class HeaderCenter extends BaseComponent
{
    protected ?CenterConfig $centerConfig = null;

    private bool $socialLinksPresent = false;

    public function __construct(
        public string $title = '',
        public ?string $tagline = null,
        public ?string $logo = null,
        public ?string $logoAlt = null,
        public ?string $url = null,
        public ?string $searchUrl = null,
        public bool $small = false,
        public bool $light = false,
    ) {}

    public function render(): Closure
    {
        return function (array $data): View {
            $this->rejectArrayAttributes(['socialLinks', 'social-links']);
            $this->centerConfig = CenterConfig::fromArray([
                'title' => $this->title,
                'tagline' => $this->tagline,
                'logo' => $this->logo,
                'logoAlt' => $this->logoAlt,
                'url' => $this->url,
                'searchUrl' => $this->searchUrl,
                'small' => $this->small,
                'light' => $this->light,
            ]);

            $social = $data['socialLinks'] ?? $data['social-links'] ?? $data['social'] ?? null;
            $slot = $data['slot'] ?? null;
            $this->socialLinksPresent = $this->hasContent($social) || $this->hasContent($slot);

            return $this->componentView('design-laravel-kit::components.header-center')
                ->with($data)
                ->with(['centerConfig' => $this->centerConfig]);
        };
    }

    private function hasContent(mixed $slot): bool
    {
        return $slot instanceof ComponentSlot
            ? $slot->isNotEmpty()
            : trim((string) $slot) !== '';
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
        return $this->socialLinksPresent;
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
