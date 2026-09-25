<?php

namespace IgorSmoleac\DesignLaravelKit\Tests\Feature;

use IgorSmoleac\DesignLaravelKit\DesignLaravelKitServiceProvider;
use Orchestra\Testbench\TestCase;

class ButtonComponentTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [DesignLaravelKitServiceProvider::class];
    }

    public function test_button_tag_renders_slot_and_default_variant(): void
    {
        $this->blade('<x-italia::button>Click</x-italia::button>')
            ->assertSee('Click')
            ->assertSee('btn-primary', false);
    }

    public function test_button_tag_with_href_renders_anchor(): void
    {
        $this->blade('<x-italia::button href="/foo">Link</x-italia::button>')
            ->assertSee('<a', false)
            ->assertDontSee('<button', false);
    }

    public function test_button_tag_renders_loading_state(): void
    {
        $this->blade('<x-italia::button loading>Save</x-italia::button>')
            ->assertSee('dlk-spinner-inline', false)
            ->assertSee('aria-busy="true"', false);
    }
}
