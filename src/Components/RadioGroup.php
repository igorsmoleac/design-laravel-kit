<?php

namespace IgorSmoleac\DesignLaravelKit\Components;

use IgorSmoleac\DesignLaravelKit\Components\Concerns\HandlesFormField;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;

class RadioGroup extends BaseFormComponent
{
    use HandlesFormField;

    public bool $grouped = true;

    public ?string $groupErrorId = null;

    public function __construct(
        public string $name,
        public ?string $legend = null,
        public ?string $hint = null,
        public bool $required = false,
        string $bag = 'default',
        ?string $wrapperClass = null,
    ) {
        $this->bag = $bag;
        $this->wrapperClass = $wrapperClass;
    }

    public function render(): View
    {
        $this->groupErrorId = $this->hasError() ? $this->errorId() : null;

        return $this->componentView('design-laravel-kit::components.radio-group');
    }

    public function legendText(): string
    {
        return $this->legend ?? Str::headline($this->name);
    }
}
