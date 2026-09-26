<?php

namespace IgorSmoleac\DesignLaravelKit\Components;

use Illuminate\Contracts\View\View;

class Modal extends BaseComponent
{
    public function __construct(
        public string $title,
        public bool $centered = false,
        public bool $scrollable = false,
        public ?string $size = null,
        public bool $static = false,
        public bool $dismissible = true,
    ) {}

    public function render(): View
    {
        return view('design-laravel-kit::components.modal');
    }

    public function dialogId(): string
    {
        return $this->id();
    }

    public function titleId(): string
    {
        return $this->dialogId() . '-title';
    }

    public function bodyId(): string
    {
        return $this->dialogId() . '-body';
    }

    public function dialogClass(): string
    {
        return collect([
            'modal-dialog',
            $this->centered ? 'modal-dialog-centered' : null,
            $this->scrollable ? 'modal-dialog-scrollable' : null,
            $this->size !== null ? 'modal-' . $this->size : null,
        ])->filter()
            ->implode(' ');
    }
}
