<?php

namespace IgorSmoleac\DesignLaravelKit\Tests\Feature;

use IgorSmoleac\DesignLaravelKit\DesignLaravelKitServiceProvider;
use Orchestra\Testbench\TestCase;

class AlertComponentTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [DesignLaravelKitServiceProvider::class];
    }

    public function test_renders_dismissible_danger_alert_with_title(): void
    {
        $this->blade('<x-italia::alert variant="danger" title="Errore" dismissible>Qualcosa è andato storto.</x-italia::alert>')
            ->assertSee('alert alert-danger alert-dismissible fade show', false)
            ->assertSee('<h4 class="alert-heading">Errore</h4>', false)
            ->assertSee('Qualcosa è andato storto.')
            ->assertSee('data-bs-dismiss="alert"', false)
            ->assertSee('role="alert"', false);
    }
}
