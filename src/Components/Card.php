<?php

namespace IgorSmoleac\DesignLaravelKit\Components;

use Illuminate\Contracts\View\View;

class Card extends BaseComponent
{
    public function __construct(
        public ?string $title = null,
        public ?string $subtitle = null,
        public ?string $image = null,
        public ?string $imageAlt = null,
        public ?string $href = null,
        public bool $big = false,
        public bool $teaser = false,
        public int $headingLevel = 3,
    ) {}

    public function headingTag(): string
    {
        $level = $this->headingLevel;

        if ($level < 2 || $level > 6) {
            $level = 3;
        }

        return 'h' . $level;
    }

    public function render(): View
    {
        return view('design-laravel-kit::components.card');
    }

    public function wrapperClass(): string
    {
        return collect(['card-wrapper', $this->teaser ? 'card-teaser-wrapper' : null])
            ->filter()
            ->implode(' ');
    }

    public function cardClass(): string
    {
        return collect([
            'card',
            $this->image !== null ? 'card-img no-after' : null,
            $this->big ? 'card-big' : null,
            $this->teaser ? 'card-teaser' : null,
        ])->filter()
            ->implode(' ');
    }

    public function imageAltText(): string
    {
        return $this->imageAlt ?? $this->title ?? '';
    }
}
