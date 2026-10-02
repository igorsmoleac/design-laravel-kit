<?php

namespace IgorSmoleac\DesignLaravelKit\Components;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ViewErrorBag;

abstract class BaseFormComponent extends BaseComponent
{
    protected string $bag = 'default';

    abstract protected function baseFieldName(): string;

    abstract protected function baseFieldHint(): ?string;

    public function errorId(): string
    {
        return $this->id() . '-error';
    }

    public function hintId(): string
    {
        return $this->id() . '-hint';
    }

    protected function idSeed(): ?string
    {
        return $this->baseFieldName();
    }

    public function hasError(): bool
    {
        return ($field = $this->errorField()) !== null
            && $this->errors()->getBag($this->bag)->has($field);
    }

    public function errorMessage(): ?string
    {
        $field = $this->errorField();

        if ($field === null) {
            return null;
        }

        return $this->errors()->getBag($this->bag)->first($field) ?: null;
    }

    protected function errors(): ViewErrorBag
    {
        $shared = View::shared('errors');

        return $shared instanceof ViewErrorBag ? $shared : new ViewErrorBag;
    }

    protected function errorField(): ?string
    {
        if ($this->baseFieldName() === '') {
            return null;
        }

        return $this->toDotNotation($this->baseFieldName());
    }

    protected function toDotNotation(string $name): string
    {
        $name = preg_replace('/\[\]$/', '', $name);
        $name = str_replace(['[', ']'], ['.', ''], $name);

        return rtrim($name, '.');
    }

    public function describedBy(): string
    {
        return collect([
            $this->hasHint() ? $this->hintId() : null,
            $this->hasError() ? $this->errorId() : null,
        ])->filter()->implode(' ');
    }

    protected function hasHint(): bool
    {
        return filled($this->baseFieldHint());
    }
}
