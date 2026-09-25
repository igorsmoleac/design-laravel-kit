<?php

namespace IgorSmoleac\DesignLaravelKit\Enums;

enum ButtonVariant: string
{
    case Primary = 'primary';
    case Secondary = 'secondary';
    case Danger = 'danger';
    case Outline = 'outline';
    case Link = 'link';

    public function cssClass(): string
    {
        return match ($this) {
            self::Outline => 'btn-outline-secondary',
            default => 'btn-' . $this->value,
        };
    }
}
