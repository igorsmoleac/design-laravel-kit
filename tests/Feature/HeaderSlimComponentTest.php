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
        $this->blade('<x-italia::header-slim ente="Comune di Roma" ente-url="https://www.comune.roma.it" :links="[[\'url\' => \'/\', \'text\' => \'Pagina iniziale\']]" :languages="[[\'code\' => \'it\', \'label\' => \'ITA\', \'active\' => true]]" login-url="/login" />')
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
