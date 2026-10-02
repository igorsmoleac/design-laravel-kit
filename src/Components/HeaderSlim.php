<?php

namespace IgorSmoleac\DesignLaravelKit\Components;

use Closure;
use IgorSmoleac\DesignLaravelKit\DTO\SlimConfig;
use Illuminate\Contracts\View\View;
use Illuminate\View\ComponentSlot;

class HeaderSlim extends BaseComponent
{
    protected ?SlimConfig $slimConfig = null;

    private bool $linksPresent = false;

    private bool $languagesPresent = false;

    public function __construct(
        public string $ente = 'Ente appartenenza',
        public ?string $enteUrl = null,
        public ?string $loginUrl = null,
        public string $loginLabel = 'Accedi',
        public ?string $languageLabel = null,
        public bool $light = false,
        public bool $sticky = false,
    ) {}

    public function render(): Closure
    {
        return function (array $data): View {
            $this->rejectArrayAttributes(['links', 'languages']);
            $this->slimConfig = SlimConfig::fromArray([
                'ente' => $this->ente,
                'enteUrl' => $this->enteUrl,
                'loginUrl' => $this->loginUrl,
                'loginLabel' => $this->loginLabel,
                'light' => $this->light,
                'sticky' => $this->sticky,
            ]);
            $this->linksPresent = $this->hasContent($data['links'] ?? null)
                || $this->hasContent($data['slot'] ?? null);
            $this->languagesPresent = $this->hasContent($data['languages'] ?? null);

            return $this->componentView('design-laravel-kit::components.header-slim')
                ->with($data)
                ->with(['slimConfig' => $this->slimConfig]);
        };
    }

    private function hasContent(mixed $slot): bool
    {
        return $slot instanceof ComponentSlot
            ? $slot->isNotEmpty()
            : trim((string) $slot) !== '';
    }

    public function hasEnteLink(): bool
    {
        return $this->enteUrl !== null;
    }

    public function hasLinks(): bool
    {
        return $this->linksPresent;
    }

    public function hasLanguages(): bool
    {
        return $this->languagesPresent;
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
}
