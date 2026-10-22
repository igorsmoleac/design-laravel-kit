<?php

namespace IgorSmoleac\DesignLaravelKit\Enums;

enum IconSpacing: string
{
    case Left = 'left';
    case Right = 'right';
    case Padded = 'padded';

    public function cssClass(): string
    {
        return 'icon-' . $this->value;
    }
}
