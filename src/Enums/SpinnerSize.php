<?php

namespace IgorSmoleac\DesignLaravelKit\Enums;

enum SpinnerSize: string
{
    case Small = 'sm';
    case Medium = 'md';
    case Large = 'lg';
    case ExtraLarge = 'xl';

    public function cssClass(): string
    {
        return 'size-' . $this->value;
    }
}
