<?php

namespace IgorSmoleac\DesignLaravelKit\Components;

use IgorSmoleac\DesignLaravelKit\DTO\ConfigValidator;
use IgorSmoleac\DesignLaravelKit\Enums\BadgeVariant;
use Illuminate\Contracts\View\View;
use InvalidArgumentException;

class Badge extends BaseComponent
{
    protected BadgeVariant $variant;

    public function __construct(
        string|BadgeVariant $variant = BadgeVariant::Primary,
        public bool $pill = false,
        public ?string $href = null,
    ) {
        ConfigValidator::optionalUrl(['href' => $this->href], 'href');

        $this->variant = $variant instanceof BadgeVariant
            ? $variant
            : BadgeVariant::tryFrom($variant) ?? throw new InvalidArgumentException("Unknown BadgeVariant: {$variant}");
    }

    public function render(): View
    {
        return $this->componentView('design-laravel-kit::components.badge');
    }

    public function variant(): BadgeVariant
    {
        return $this->variant;
    }

    public function cssClass(): string
    {
        return collect([
            'badge',
            $this->pill ? 'rounded-pill' : null,
            $this->variant->cssClass(),
        ])->filter()->implode(' ');
    }

    public function tagName(): string
    {
        return $this->href !== null ? 'a' : 'span';
    }
}
