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
        public ?string $description = null,
    ) {}

    public function render(): View
    {
        return view('design-laravel-kit::components.modal');
    }

    /**
     * Returns the modal's root ID.
     *
     * Without an explicit ID, the generated ID cannot be referenced by a
     * static `data-bs-target` trigger. Always pass an ID when using a trigger button.
     */
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

    public function hasDescription(): bool
    {
        return $this->description !== null;
    }

    public function descriptionId(): string
    {
        return $this->dialogId() . '-description';
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
