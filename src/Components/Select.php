<?php

namespace IgorSmoleac\DesignLaravelKit\Components;

use IgorSmoleac\DesignLaravelKit\Components\Concerns\HandlesFormField;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;

class Select extends BaseFormComponent
{
    use HandlesFormField;

    /** @var array<array-key, mixed> */
    public array $options;

    /**
     * @param  array<array-key, mixed>|Collection<array-key, mixed>|Arrayable<array-key, mixed>  $options
     * @param  array<array-key, mixed>  $selected
     */
    public function __construct(
        public string $name,
        array|Collection|Arrayable $options = [],
        public ?string $label = null,
        public ?string $placeholder = null,
        public string|int|float|\BackedEnum|array|null $selected = null,
        public ?string $hint = null,
        public bool $multiple = false,
        public bool $required = false,
        public bool $disabled = false,
        public bool $floating = true,
        public string $bag = 'default',
        ?string $wrapperClass = null,
    ) {
        $this->options = $this->normalizeOptions($options);
        $this->wrapperClass = $wrapperClass;
    }

    /**
     * @param  array<array-key, mixed>|Collection<array-key, mixed>|Arrayable<array-key, mixed>  $options
     * @return array<array-key, mixed>
     */
    protected function normalizeOptions(array|Collection|Arrayable $options): array
    {
        if (is_array($options)) {
            return $options;
        }

        return $options->toArray();
    }

    public function render(): View
    {
        return $this->componentView('design-laravel-kit::components.select');
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

    /**
     * @return string|array<array-key, string|null>|null
     */
    public function selectedValue(): string|array|null
    {
        $selected = session()->getOldInput($this->errorField() ?? $this->name) ?? $this->selected;

        if (is_array($selected)) {
            return array_map(fn ($value) => $this->normalizeValue($value), $selected);
        }

        return $this->normalizeValue($selected);
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

        return is_array($selected) ? $selected !== [] : $this->normalizeValue($selected) !== null;
    }
}
