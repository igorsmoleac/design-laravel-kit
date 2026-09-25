<?php

namespace IgorSmoleac\DesignLaravelKit\Tests\Unit\Components;

use IgorSmoleac\DesignLaravelKit\Components\Icon;
use IgorSmoleac\DesignLaravelKit\DesignLaravelKitServiceProvider;
use IgorSmoleac\DesignLaravelKit\Enums\IconSize;
use Orchestra\Testbench\TestCase;

class IconTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [DesignLaravelKitServiceProvider::class];
    }

    public function test_default_size_renders_base_icon_class(): void
    {
        $this->assertSame('icon', (new Icon('search'))->svgClass());
    }

    public function test_small_size_renders_base_and_modifier_classes(): void
    {
        $this->assertSame('icon icon-sm', (new Icon('search', IconSize::Small))->svgClass());
    }

    public function test_extra_large_size_renders_base_and_modifier_classes(): void
    {
        $this->assertSame('icon icon-xl', (new Icon('search', IconSize::ExtraLarge))->svgClass());
    }

    public function test_spacing_adds_modifier_class(): void
    {
        $icon = new Icon('search', spacing: 'right');

        $this->assertSame('icon icon-right', $icon->svgClass());
    }

    public function test_name_receives_it_prefix(): void
    {
        $this->assertSame('it-search', (new Icon('search'))->iconName());
    }

    public function test_existing_prefix_is_preserved(): void
    {
        $this->assertSame('it-search', (new Icon('it-search'))->iconName());
    }

    public function test_sprite_url_points_to_published_sprite(): void
    {
        $this->assertStringContainsString(
            'vendor/design-laravel-kit/svg/sprites.svg',
            (new Icon('search'))->spriteUrl()
        );
    }

    public function test_decorative_icon_is_hidden_from_assistive_tech(): void
    {
        $html = (string) $this->blade('<x-italia::icon name="search" />');

        $this->assertStringContainsString('aria-hidden="true"', $html);
        $this->assertStringNotContainsString('role="img"', $html);
    }

    public function test_labelled_icon_is_exposed_to_assistive_tech(): void
    {
        $html = (string) $this->blade('<x-italia::icon name="search" label="Cerca" />');

        $this->assertStringContainsString('role="img"', $html);
        $this->assertStringContainsString('aria-label="Cerca"', $html);
        $this->assertStringNotContainsString('aria-hidden', $html);
    }

    public function test_use_reference_targets_sprite_symbol(): void
    {
        $html = (string) $this->blade('<x-italia::icon name="search" />');

        $this->assertStringContainsString('#it-search', $html);
    }

    public function test_sprite_url_is_absolute(): void
    {
        $html = (string) $this->blade('<x-italia::icon name="search" />');

        $this->assertStringContainsString('href="http://', $html);
    }

    public function test_custom_class_is_merged(): void
    {
        $html = (string) $this->blade('<x-italia::icon name="search" class="my-class" />');

        $this->assertStringContainsString('my-class', $html);
        $this->assertStringContainsString('icon', $html);
    }

    public function test_size_accepts_string_from_tag(): void
    {
        $html = (string) $this->blade('<x-italia::icon name="search" size="lg" />');

        $this->assertStringContainsString('icon-lg', $html);
    }
}
