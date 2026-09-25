<?php

namespace IgorSmoleac\DesignLaravelKit\Enums;

enum IconSize: string
{
    case Small = 'sm';
    case Medium = 'md';
    case Large = 'lg';
    case ExtraLarge = 'xl';

    public function cssClass(): string
    {
        return match ($this) {
            self::Medium => 'icon',
            default => 'icon-' . $this->value,
        };
    }
}
