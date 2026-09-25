<?php

namespace IgorSmoleac\DesignLaravelKit\Components;

use IgorSmoleac\DesignLaravelKit\Components\Concerns\HandlesFormField;
use Illuminate\Contracts\View\View;

class Select extends BaseComponent
{
    use HandlesFormField;

    public function __construct(
        public string $name,
        public array $options = [],
        public ?string $label = null,
        public ?string $placeholder = null,
        public string|array|null $selected = null,
        public ?string $hint = null,
        public bool $multiple = false,
        public bool $required = false,
        public bool $disabled = false,
        public bool $floating = true,
        public string $bag = 'default',
    ) {}

    public function render(): View
    {
        return view('design-laravel-kit::components.select');
    }

    public function inputClass(): string
    {
        return collect(['form-select', $this->hasError() ? 'is-invalid' : null])
            ->filter()
            ->implode(' ');
    }

    /**
     * Native <select multiple> posts an array, so the name must end with [].
     */
    public function fieldName(): string
    {
        if (! $this->multiple || str_ends_with($this->name, '[]')) {
            return $this->name;
        }

        return $this->name . '[]';
    }

    public function selectedValue(): string|array|null
    {
        if ($this->selected !== null) {
            return $this->selected;
        }

        return session()->getOldInput($this->errorField() ?? $this->name);
    }

    public function isSelected(string|int $value): bool
    {
        $selected = $this->selectedValue();

        if (is_array($selected)) {
            return in_array((string) $value, array_map(strval(...), $selected), true);
        }

        return (string) $selected === (string) $value;
    }

    public function hasValue(): bool
    {
        $selected = $this->selectedValue();

        return $selected !== null && $selected !== [];
    }
}
