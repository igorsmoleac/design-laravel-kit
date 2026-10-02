<?php

namespace IgorSmoleac\DesignLaravelKit\Tests\Feature;

use IgorSmoleac\DesignLaravelKit\DesignLaravelKitServiceProvider;
use Orchestra\Testbench\TestCase;

class HeaderSlimComponentTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [DesignLaravelKitServiceProvider::class];
    }

    public function test_renders_full_header_slim(): void
    {
        $this->blade(<<<'BLADE'
<x-italia::header-slim ente="Comune di Roma" ente-url="https://www.comune.roma.it" login-url="/login">
    <x-slot:links><li><a class="dropdown-item list-item" href="/">Pagina iniziale</a></li></x-slot:links>
    <x-slot:languages><li><a class="dropdown-item list-item" href="/">ITA</a></li></x-slot:languages>
</x-italia::header-slim>
BLADE)
            ->assertSee('it-header-slim-wrapper', false)
            ->assertSee('Comune di Roma')
            ->assertSee('Accedi')
            ->assertSee('data-bs-toggle="dropdown"', false)
            ->assertSee('aria-controls="dlk-header-slim', false);
    }

    public function test_renders_minimal_header_slim(): void
    {
        $this->blade('<x-italia::header-slim />')
            ->assertSee('it-header-slim-wrapper-content', false)
            ->assertDontSee('nav-mobile', false)
            ->assertDontSee('dropdown-menu', false)
            ->assertDontSee('Accedi', false);
    }

    public function test_renders_light_theme(): void
    {
        $this->blade('<x-italia::header-slim light />')
            ->assertSee('it-header-slim-wrapper theme-light', false);
    }
}
