<?php

namespace IgorSmoleac\DesignLaravelKit\Components;

use Illuminate\Contracts\View\View;

class Layout extends BaseComponent
{
    public string $skipLabel;

    /**
     * @param  array<string, mixed>  $slim
     * @param  array<string, mixed>  $center
     * @param  array<string, mixed>  $navbar
     * @param  array<string, mixed>  $footer
     */
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
        ?string $skipLabel = null,
        public string $bodyClass = '',
        public ?string $mainClass = null,
    ) {
        $this->skipLabel = $skipLabel ?? __('design-laravel-kit::Vai al contenuto principale');
    }

    public function render(): View
    {
        return $this->componentView('design-laravel-kit::components.layout');
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
