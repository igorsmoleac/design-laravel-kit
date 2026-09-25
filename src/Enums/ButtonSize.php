<?php

namespace IgorSmoleac\DesignLaravelKit\Enums;

enum ButtonSize: string
{
    case Small = 'sm';
    case Medium = 'md';
    case Large = 'lg';

    public function cssClass(): string
    {
        return match ($this) {
            self::Medium => 'btn',
            default => 'btn-' . $this->value,
        };
    }
}
