<?php

namespace IgorSmoleac\DesignLaravelKit\Enums;

enum AlertVariant: string
{
    case Info = 'info';
    case Success = 'success';
    case Warning = 'warning';
    case Danger = 'danger';

    public function cssClass(): string
    {
        return 'alert-' . $this->value;
    }
}
