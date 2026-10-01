<?php

namespace IgorSmoleac\DesignLaravelKit\Components;

use IgorSmoleac\DesignLaravelKit\Components\Concerns\HandlesFormField;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;

class Select extends BaseFormComponent
{
    use HandlesFormField;

    public array $options;

    public function __construct(
        public string $name,
        array|Collection|Arrayable $options = [],
        public ?string $label = null,
        public ?string $placeholder = null,
        public string|array|null $selected = null,
        public ?string $hint = null,
        public bool $multiple = false,
        public bool $required = false,
        public bool $disabled = false,
        public bool $floating = true,
        public string $bag = 'default',
    ) {
        $this->options = $this->normalizeOptions($options);
    }

    protected function normalizeOptions(array|Collection|Arrayable $options): array
    {
        if (is_array($options)) {
            return $options;
        }

        return $options->toArray();
    }

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
        $old = session()->getOldInput($this->errorField() ?? $this->name);

        return $old ?? $this->selected;
    }

    public function isSelected(string|int $value): bool
    {
        $selected = $this->selectedValue();

        if ($selected === null) {
            return false;
        }

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
