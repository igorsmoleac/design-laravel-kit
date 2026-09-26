<?php

namespace IgorSmoleac\DesignLaravelKit\Tests\Unit\Components;

use IgorSmoleac\DesignLaravelKit\DesignLaravelKitServiceProvider;
use Orchestra\Testbench\TestCase;

class CieButtonTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [DesignLaravelKitServiceProvider::class];
    }

    public function test_renders_basic_button(): void
    {
        $html = (string) $this->blade('<x-italia::cie-button />');

        $this->assertStringContainsString('dlk-cie-button', $html);
        $this->assertStringContainsString('dlk-cie-label', $html);
    }

    public function test_default_href_is_cie_login(): void
    {
        $html = (string) $this->blade('<x-italia::cie-button />');

        $this->assertStringContainsString('href="/cie/login"', $html);
    }

    public function test_custom_href_is_used(): void
    {
        $html = (string) $this->blade('<x-italia::cie-button href="/custom-cie" />');

        $this->assertStringContainsString('href="/custom-cie"', $html);
        $this->assertStringNotContainsString('/cie/login', $html);
    }

    public function test_all_sizes_render_size_class(): void
    {
        foreach (['s', 'm', 'l', 'xl'] as $size) {
            $html = (string) $this->blade("<x-italia::cie-button size=\"{$size}\" />");

            $this->assertStringContainsString('dlk-cie-button-' . $size, $html);
        }
    }

    public function test_default_label_is_official_text(): void
    {
        $html = (string) $this->blade('<x-italia::cie-button />');

        $this->assertStringContainsString('Entra con CIE', $html);
    }

    public function test_custom_label_is_used(): void
    {
        $html = (string) $this->blade('<x-italia::cie-button label="Accedi con CIE" />');

        $this->assertStringContainsString('Accedi con CIE', $html);
        $this->assertStringContainsString('aria-label="Accedi con CIE"', $html);
    }

    public function test_cie_logo_is_rendered(): void
    {
        $html = (string) $this->blade('<x-italia::cie-button />');

        $this->assertStringContainsString('<img class="dlk-cie-logo"', $html);
        $this->assertStringContainsString('data:image/png;base64,', $html);
        $this->assertStringContainsString('aria-hidden="true"', $html);
    }

    public function test_custom_attributes_passed_through(): void
    {
        $html = (string) $this->blade('<x-italia::cie-button data-test="cie" />');

        $this->assertStringContainsString('data-test="cie"', $html);
    }

    public function test_renders_as_anchor_not_button(): void
    {
        $html = (string) $this->blade('<x-italia::cie-button />');

        $this->assertStringContainsString('<a ', $html);
        $this->assertStringNotContainsString('<button', $html);
    }
}
