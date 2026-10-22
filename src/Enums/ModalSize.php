<?php

namespace IgorSmoleac\DesignLaravelKit\Enums;

enum ModalSize: string
{
    case Small = 'sm';
    case Large = 'lg';
    case ExtraLarge = 'xl';

    public function cssClass(): string
    {
        return 'modal-' . $this->value;
    }
}
