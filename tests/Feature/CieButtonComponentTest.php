<?php

namespace IgorSmoleac\DesignLaravelKit\Tests\Feature;

use IgorSmoleac\DesignLaravelKit\DesignLaravelKitServiceProvider;
use Orchestra\Testbench\TestCase;

class CieButtonComponentTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [DesignLaravelKitServiceProvider::class];
    }

    public function test_renders_official_cie_button_with_logo(): void
    {
        $this->blade('<x-italia::cie-button />')
            ->assertSee('href="/cie/login"', false)
            ->assertSee('Entra con CIE')
            ->assertSee('aria-label="Entra con CIE"', false)
            ->assertSee('<img class="dlk-cie-logo"', false);
    }
}
