<?php

namespace IgorSmoleac\DesignLaravelKit\Components;

use IgorSmoleac\DesignLaravelKit\DTO\ConfigValidator;
use Illuminate\Contracts\View\View;

class SpidButton extends BaseComponent
{
    /**
     * @param  list<array<string, mixed>>  $providers
     */
    public function __construct(
        public ?string $href = null,
        public string $size = 'm',
        public string $label = 'Entra con SPID',
        public bool $dropdown = false,
        public array $providers = [],
    ) {
        ConfigValidator::optionalUrl(['href' => $this->href], 'href');
    }

    public function render(): View
    {
        return $this->componentView('design-laravel-kit::components.spid-button');
    }

    public function cssClass(): string
    {
        return collect(['dlk-spid-button', 'dlk-spid-button-' . $this->size])
            ->implode(' ');
    }

    public function loginUrl(): string
    {
        return $this->href ?? '/spid/login';
    }

    public function hasDropdown(): bool
    {
        return $this->dropdown;
    }

    /** @return array<array-key, mixed> */
    public function providerList(): array
    {
        if (! $this->dropdown) {
            return [];
        }

        if ($this->providers !== []) {
            return $this->validatedProviders($this->providers);
        }

        $providers = config('design-laravel-kit.spid.providers', []);

        return is_array($providers) ? $this->validatedProviders($providers) : [];
    }

    /**
     * @param  array<array-key, mixed>  $providers
     * @return array<array-key, mixed>
     */
    private function validatedProviders(array $providers): array
    {
        foreach ($providers as $index => $provider) {
            if (! is_array($provider) || ! is_string($provider['url'] ?? null)) {
                continue;
            }

            ConfigValidator::assertUrl($provider['url'], "providers.{$index}.url");
        }

        return $providers;
    }
}
