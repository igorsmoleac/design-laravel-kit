<?php

namespace IgorSmoleac\DesignLaravelKit\Components;

use IgorSmoleac\DesignLaravelKit\Enums\AlertVariant;
use Illuminate\Contracts\View\View;

class Alert extends BaseComponent
{
    protected AlertVariant $variant;

    public function __construct(
        string|AlertVariant $variant = AlertVariant::Info,
        public ?string $title = null,
        public bool $dismissible = false,
        public bool $icon = false,
    ) {
        $this->variant = $variant instanceof AlertVariant
            ? $variant
            : AlertVariant::tryFrom($variant) ?? AlertVariant::Info;
    }

    public function render(): View
    {
        return view('design-laravel-kit::components.alert');
    }

    public function variant(): AlertVariant
    {
        return $this->variant;
    }

    public function cssClass(): string
    {
        return collect([
            'alert',
            $this->variant->cssClass(),
            $this->dismissible ? 'alert-dismissible fade show' : null,
        ])->filter()->implode(' ');
    }

    public function iconName(): string
    {
        return match ($this->variant) {
            AlertVariant::Info => 'it-info-circle',
            AlertVariant::Success => 'it-check-circle',
            AlertVariant::Warning => 'it-warning',
            AlertVariant::Danger => 'it-error',
        };
    }
}
