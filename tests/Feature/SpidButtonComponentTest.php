<?php

namespace IgorSmoleac\DesignLaravelKit\Tests\Feature;

use IgorSmoleac\DesignLaravelKit\DesignLaravelKitServiceProvider;
use Orchestra\Testbench\TestCase;

class SpidButtonComponentTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [DesignLaravelKitServiceProvider::class];
    }

    public function test_renders_official_spid_button_with_logo(): void
    {
        $this->blade('<x-italia::spid-button />')
            ->assertSee('href="/spid/login"', false)
            ->assertSee('Entra con SPID')
            ->assertSee('aria-label="Entra con SPID"', false)
            ->assertSee('<svg class="dlk-spid-logo"', false);
    }
}
