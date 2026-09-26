<?php

namespace IgorSmoleac\DesignLaravelKit\Tests\Feature;

use IgorSmoleac\DesignLaravelKit\DesignLaravelKitServiceProvider;
use Orchestra\Testbench\TestCase;

class ModalComponentTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [DesignLaravelKitServiceProvider::class];
    }

    public function test_renders_accessible_dialog_with_footer(): void
    {
        $this->blade('<x-italia::modal id="demo" title="Conferma">Sei sicuro?<x-slot:footer><button>Annulla</button></x-slot:footer></x-italia::modal>')
            ->assertSee('role="dialog"', false)
            ->assertSee('aria-modal="true"', false)
            ->assertSee('aria-labelledby="demo-title"', false)
            ->assertSee('<h2 class="modal-title h5" id="demo-title">Conferma</h2>', false)
            ->assertSee('Sei sicuro?')
            ->assertSee('<div class="modal-footer">', false)
            ->assertSee('<button>Annulla</button>', false);
    }
}
