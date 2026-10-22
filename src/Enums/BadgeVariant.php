<?php

namespace IgorSmoleac\DesignLaravelKit\Enums;

enum BadgeVariant: string
{
    case Primary = 'primary';
    case Secondary = 'secondary';
    case Success = 'success';
    case Danger = 'danger';
    case Warning = 'warning';
    case Info = 'info';
    case Light = 'light';
    case Dark = 'dark';

    public function cssClass(): string
    {
        return 'bg-' . $this->value;
    }
}
