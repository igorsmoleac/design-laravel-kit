<?php

namespace IgorSmoleac\DesignLaravelKit\Components;

use IgorSmoleac\DesignLaravelKit\Components\Concerns\HandlesFormField;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;

class CheckboxGroup extends BaseComponent
{
    use HandlesFormField;

    protected string $bag;

    public bool $grouped = true;

    public ?string $groupErrorId = null;

    public function __construct(
        public string $name,
        public ?string $legend = null,
        public ?string $hint = null,
        public bool $required = false,
        public string $errorBag = 'default',
    ) {
        $this->bag = $this->errorBag;
    }

    public function render(): View
    {
        $this->groupErrorId = $this->hasError() ? $this->errorId() : null;

        return view('design-laravel-kit::components.checkbox-group');
    }

    public function legendText(): string
    {
        return $this->legend ?? Str::headline($this->name);
    }
}
