<?php

namespace IgorSmoleac\DesignLaravelKit\Components;

use IgorSmoleac\DesignLaravelKit\Enums\ModalSize;
use Illuminate\Contracts\View\View;
use InvalidArgumentException;

class Modal extends BaseComponent
{
    protected ?ModalSize $size = null;

    public function __construct(
        public string $title,
        public bool $centered = false,
        public bool $scrollable = false,
        ModalSize|string|null $size = null,
        public bool $static = false,
        public bool $dismissible = true,
        public ?string $description = null,
    ) {
        $this->size = $size === null
            ? null
            : ($size instanceof ModalSize
                ? $size
                : ModalSize::tryFrom($size) ?? throw new InvalidArgumentException("Unknown ModalSize: {$size}"));
    }

    public function render(): View
    {
        return $this->componentView('design-laravel-kit::components.modal');
    }

    public function size(): ?ModalSize
    {
        return $this->size;
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
            $this->size?->cssClass(),
        ])->filter()
            ->implode(' ');
    }
}
