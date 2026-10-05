<?php

namespace IgorSmoleac\DesignLaravelKit\Components;

use Closure;
use IgorSmoleac\DesignLaravelKit\DTO\ConfigValidator;
use Illuminate\Contracts\View\View;

class HeaderMegamenu extends BaseComponent
{
    public function __construct(
        public string $text,
        public ?string $url = null,
        public bool $active = false,
    ) {
        ConfigValidator::requiredString(['text' => $this->text], 'text');
        ConfigValidator::assertUrl($this->url, 'url');
    }

    public function render(): Closure
    {
        return function (array $data): View {
            $this->rejectArrayAttributes(['items', 'megamenu', 'sections']);

            view()->share('dlkHasMegamenu', true);

            return $this->componentView('design-laravel-kit::components.header-megamenu')
                ->with($data);
        };
    }
}
