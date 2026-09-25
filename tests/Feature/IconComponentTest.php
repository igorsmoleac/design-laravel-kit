<?php

namespace IgorSmoleac\DesignLaravelKit\Tests\Feature;

use IgorSmoleac\DesignLaravelKit\DesignLaravelKitServiceProvider;
use Orchestra\Testbench\TestCase;

class IconComponentTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [DesignLaravelKitServiceProvider::class];
    }

    public function test_icon_tag_resolves_through_component_namespace(): void
    {
        $this->blade('<x-italia::icon name="search" />')
            ->assertSee('#it-search', false)
            ->assertSee('icon', false);
    }

    public function test_icon_tag_renders_size_and_label(): void
    {
        $this->blade('<x-italia::icon name="it-search" size="xl" label="Cerca" />')
            ->assertSee('icon-xl', false)
            ->assertSee('aria-label="Cerca"', false);
    }
}
