<?php

namespace IgorSmoleac\DesignLaravelKit\Tests\Feature;

use IgorSmoleac\DesignLaravelKit\DesignLaravelKitServiceProvider;
use Orchestra\Testbench\TestCase;

class CardComponentTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [DesignLaravelKitServiceProvider::class];
    }

    public function test_renders_clickable_card_with_image_and_actions(): void
    {
        $this->blade('<x-italia::card title="Titolo" image="https://example.com/a.jpg" href="/dettaglio">Testo<x-slot:actions><button>Azione</button></x-slot:actions></x-italia::card>')
            ->assertSee('<a class="card-wrapper" href="/dettaglio">', false)
            ->assertSee('card-img no-after', false)
            ->assertSee('<h3 class="card-title h5">Titolo</h3>', false)
            ->assertSee('Testo')
            ->assertSee('<button>Azione</button>', false);
    }
}
