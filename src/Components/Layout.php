<?php

namespace IgorSmoleac\DesignLaravelKit\Components;

use Illuminate\Contracts\View\View;

class Layout extends BaseComponent
{
    public function __construct(
        public string $title = '',
        public ?string $description = null,
        public ?string $lang = null,
        public array $slim = [],
        public array $center = [],
        public array $navbar = [],
        public array $footer = [],
        public bool $light = false,
        public bool $sticky = false,
        public bool $skipToContent = true,
        public string $skipLabel = 'Vai al contenuto principale',
        public string $bodyClass = '',
        public ?string $mainClass = null,
    ) {}

    public function render(): View
    {
        return view('design-laravel-kit::components.layout');
    }

    public function pageTitle(): string
    {
        return filled($this->title) ? $this->title : (string) config('app.name');
    }

    public function htmlLang(): string
    {
        return $this->lang ?? app()->getLocale();
    }

    public function hasDescription(): bool
    {
        return filled($this->description);
    }

    public function bodyClasses(): string
    {
        return collect(['bg-white', $this->bodyClass])
            ->filter()
            ->implode(' ');
    }
}
