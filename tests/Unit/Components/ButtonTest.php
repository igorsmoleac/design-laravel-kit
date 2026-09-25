<?php

namespace IgorSmoleac\DesignLaravelKit\Tests\Unit\Components;

use IgorSmoleac\DesignLaravelKit\DesignLaravelKitServiceProvider;
use Orchestra\Testbench\TestCase;

class ButtonTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [DesignLaravelKitServiceProvider::class];
    }

    public function test_default_renders_primary_button(): void
    {
        $html = (string) $this->blade('<x-italia::button>Click</x-italia::button>');

        $this->assertStringContainsString('<button', $html);
        $this->assertStringContainsString('type="button"', $html);
        $this->assertStringContainsString('class="btn btn-primary"', $html);
        $this->assertStringContainsString('Click', $html);
    }

    public function test_variants_render_their_classes(): void
    {
        $this->assertStringContainsString('btn-secondary', (string) $this->blade('<x-italia::button variant="secondary">X</x-italia::button>'));
        $this->assertStringContainsString('btn-danger', (string) $this->blade('<x-italia::button variant="danger">X</x-italia::button>'));
        $this->assertStringContainsString('btn-outline-secondary', (string) $this->blade('<x-italia::button variant="outline">X</x-italia::button>'));
        $this->assertStringContainsString('btn-link', (string) $this->blade('<x-italia::button variant="link">X</x-italia::button>'));
    }

    public function test_sizes_render_their_classes(): void
    {
        $this->assertStringContainsString('btn-sm', (string) $this->blade('<x-italia::button size="sm">X</x-italia::button>'));
        $this->assertStringNotContainsString('btn-md', (string) $this->blade('<x-italia::button>X</x-italia::button>'));
        $this->assertStringContainsString('btn-lg', (string) $this->blade('<x-italia::button size="lg">X</x-italia::button>'));
    }

    public function test_href_renders_anchor_instead_of_button(): void
    {
        $html = (string) $this->blade('<x-italia::button href="/foo">Link</x-italia::button>');

        $this->assertStringContainsString('<a', $html);
        $this->assertStringContainsString('href="/foo"', $html);
        $this->assertStringNotContainsString('<button', $html);
    }

    public function test_disabled_button_has_disabled_attribute(): void
    {
        $html = (string) $this->blade('<x-italia::button disabled>Off</x-italia::button>');

        $this->assertMatchesRegularExpression('/<button[^>]*\sdisabled[\s>]/', $html);
        $this->assertStringContainsString('aria-disabled="true"', $html);
    }

    public function test_disabled_link_uses_class_and_aria(): void
    {
        $html = (string) $this->blade('<x-italia::button href="/foo" disabled>Off</x-italia::button>');

        $this->assertStringContainsString('class="btn btn-primary disabled"', $html);
        $this->assertStringContainsString('aria-disabled="true"', $html);
        $this->assertStringContainsString('tabindex="-1"', $html);
        $this->assertStringNotContainsString('<button', $html);
    }

    public function test_loading_shows_spinner_and_sets_aria_busy(): void
    {
        $html = (string) $this->blade('<x-italia::button loading>Loading</x-italia::button>');

        $this->assertStringContainsString('progress-spinner', $html);
        $this->assertStringContainsString('aria-busy="true"', $html);
        $this->assertStringContainsString('aria-disabled="true"', $html);
    }

    public function test_type_submit(): void
    {
        $html = (string) $this->blade('<x-italia::button type="submit">Send</x-italia::button>');

        $this->assertStringContainsString('type="submit"', $html);
    }

    public function test_custom_attributes_pass_through(): void
    {
        $html = (string) $this->blade('<x-italia::button wire:click="save()" data-test="btn">X</x-italia::button>');

        $this->assertStringContainsString('wire:click="save()"', $html);
        $this->assertStringContainsString('data-test="btn"', $html);
    }

    public function test_custom_class_does_not_replace_btn(): void
    {
        $html = (string) $this->blade('<x-italia::button class="custom">X</x-italia::button>');

        $this->assertStringContainsString('btn btn-primary custom', $html);
    }

    public function test_block_adds_block_class(): void
    {
        $html = (string) $this->blade('<x-italia::button block>Wide</x-italia::button>');

        $this->assertStringContainsString('btn-block', $html);
    }

    public function test_unknown_variant_falls_back_to_class_without_error(): void
    {
        $html = (string) $this->blade('<x-italia::button variant="magenta">X</x-italia::button>');

        $this->assertStringContainsString('btn-magenta', $html);
    }
}
