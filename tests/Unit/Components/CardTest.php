<?php

namespace IgorSmoleac\DesignLaravelKit\Tests\Unit\Components;

use IgorSmoleac\DesignLaravelKit\DesignLaravelKitServiceProvider;
use Orchestra\Testbench\TestCase;

class CardTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [DesignLaravelKitServiceProvider::class];
    }

    public function test_renders_basic_card(): void
    {
        $html = (string) $this->blade('<x-italia::card>Testo</x-italia::card>');

        $this->assertStringContainsString('card-wrapper', $html);
        $this->assertStringContainsString('class="card"', $html);
        $this->assertStringContainsString('card-body', $html);
    }

    public function test_title_renders_as_card_title(): void
    {
        $html = (string) $this->blade('<x-italia::card title="Titolo">Testo</x-italia::card>');

        $this->assertStringContainsString('<h3 class="card-title h5">Titolo</h3>', $html);
    }

    public function test_subtitle_renders_as_card_subtitle(): void
    {
        $html = (string) $this->blade('<x-italia::card title="T" subtitle="Sottotitolo">Testo</x-italia::card>');

        $this->assertStringContainsString('<h6 class="card-subtitle">Sottotitolo</h6>', $html);
    }

    public function test_no_title_renders_no_heading(): void
    {
        $html = (string) $this->blade('<x-italia::card>Testo</x-italia::card>');

        $this->assertStringNotContainsString('card-title', $html);
        $this->assertStringNotContainsString('card-subtitle', $html);
    }

    public function test_image_renders_responsive_wrapper(): void
    {
        $html = (string) $this->blade('<x-italia::card title="T" image="https://example.com/a.jpg">Testo</x-italia::card>');

        $this->assertStringContainsString('card-img no-after', $html);
        $this->assertStringContainsString('img-responsive-wrapper', $html);
        $this->assertStringContainsString('img-responsive', $html);
        $this->assertStringContainsString('img-wrapper', $html);
        $this->assertStringContainsString('src="https://example.com/a.jpg"', $html);
    }

    public function test_image_alt_defaults_to_title(): void
    {
        $html = (string) $this->blade('<x-italia::card title="Titolo" image="https://example.com/a.jpg">Testo</x-italia::card>');

        $this->assertStringContainsString('alt="Titolo"', $html);
    }

    public function test_image_alt_can_be_overridden(): void
    {
        $html = (string) $this->blade('<x-italia::card title="T" image="https://example.com/a.jpg" imageAlt="Descrizione">Testo</x-italia::card>');

        $this->assertStringContainsString('alt="Descrizione"', $html);
        $this->assertStringNotContainsString('alt="T"', $html);
    }

    public function test_no_image_renders_no_img(): void
    {
        $html = (string) $this->blade('<x-italia::card>Testo</x-italia::card>');

        $this->assertStringNotContainsString('<img', $html);
        $this->assertStringNotContainsString('img-responsive-wrapper', $html);
        $this->assertStringContainsString('class="card"', $html);
    }

    public function test_href_renders_clickable_wrapper(): void
    {
        $html = (string) $this->blade('<x-italia::card title="T" href="/dettaglio">Testo</x-italia::card>');

        $this->assertStringContainsString('<a class="card-wrapper" href="/dettaglio">', $html);
        $this->assertStringNotContainsString('<div class="card-wrapper"', $html);
    }

    public function test_no_href_renders_div_wrapper(): void
    {
        $html = (string) $this->blade('<x-italia::card>Testo</x-italia::card>');

        $this->assertStringContainsString('<div class="card-wrapper">', $html);
        $this->assertStringNotContainsString('<a class="card-wrapper"', $html);
    }

    public function test_slot_content_renders_in_card_text(): void
    {
        $html = (string) $this->blade('<x-italia::card>Contenuto della card.</x-italia::card>');

        $this->assertStringContainsString('<div class="card-text">Contenuto della card.</div>', $html);
    }

    public function test_actions_slot_renders(): void
    {
        $html = (string) $this->blade('<x-italia::card>Testo<x-slot:actions><button>Azione</button></x-slot:actions></x-italia::card>');

        $this->assertStringContainsString('<button>Azione</button>', $html);
    }

    public function test_no_actions_slot_renders_no_actions_block(): void
    {
        $html = (string) $this->blade('<x-italia::card>Testo</x-italia::card>');

        $this->assertStringNotContainsString('mt-3', $html);
    }

    public function test_big_renders_card_big(): void
    {
        $html = (string) $this->blade('<x-italia::card big>Testo</x-italia::card>');

        $this->assertStringContainsString('card card-big', $html);
    }

    public function test_teaser_renders_teaser_classes(): void
    {
        $html = (string) $this->blade('<x-italia::card teaser>Testo</x-italia::card>');

        $this->assertStringContainsString('card-wrapper card-teaser-wrapper', $html);
        $this->assertStringContainsString('card card-teaser', $html);
    }

    public function test_custom_class_passed_through(): void
    {
        $html = (string) $this->blade('<x-italia::card class="mb-4">Testo</x-italia::card>');

        $this->assertStringContainsString('card-wrapper mb-4', $html);
    }
}
