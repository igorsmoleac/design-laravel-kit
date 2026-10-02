<?php

namespace IgorSmoleac\DesignLaravelKit\Tests\Unit\Components;

use IgorSmoleac\DesignLaravelKit\DesignLaravelKitServiceProvider;
use Orchestra\Testbench\TestCase;

class SpidButtonTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [DesignLaravelKitServiceProvider::class];
    }

    public function test_renders_basic_button(): void
    {
        $html = (string) $this->blade('<x-italia::spid-button />');

        $this->assertStringContainsString('dlk-spid-button', $html);
        $this->assertStringContainsString('dlk-spid-label', $html);
    }

    public function test_default_href_is_spid_login(): void
    {
        $html = (string) $this->blade('<x-italia::spid-button />');

        $this->assertStringContainsString('href="/spid/login"', $html);
    }

    public function test_custom_href_is_used(): void
    {
        $html = (string) $this->blade('<x-italia::spid-button href="/custom-login" />');

        $this->assertStringContainsString('href="/custom-login"', $html);
        $this->assertStringNotContainsString('/spid/login', $html);
    }

    public function test_all_sizes_render_size_class(): void
    {
        foreach (['s', 'm', 'l', 'xl'] as $size) {
            $html = (string) $this->blade("<x-italia::spid-button size=\"{$size}\" />");

            $this->assertStringContainsString('dlk-spid-button-' . $size, $html);
        }
    }

    public function test_default_label_is_official_agid_text(): void
    {
        $html = (string) $this->blade('<x-italia::spid-button />');

        $this->assertStringContainsString('Entra con SPID', $html);
    }

    public function test_custom_label_is_used(): void
    {
        $html = (string) $this->blade('<x-italia::spid-button label="Accedi con SPID" />');

        $this->assertStringContainsString('Accedi con SPID', $html);
        $this->assertStringNotContainsString('Entra con SPID', $html);
    }

    public function test_aria_label_matches_label(): void
    {
        $html = (string) $this->blade('<x-italia::spid-button label="Accedi con SPID" />');

        $this->assertStringContainsString('aria-label="Accedi con SPID"', $html);
    }

    public function test_spid_logo_is_rendered(): void
    {
        $html = (string) $this->blade('<x-italia::spid-button />');

        $this->assertStringContainsString('<svg class="dlk-spid-logo"', $html);
        $this->assertStringContainsString('viewBox="0 0 587.6 587.6"', $html);
        $this->assertStringContainsString('aria-hidden="true"', $html);
    }

    public function test_dropdown_uses_default_providers_when_list_is_empty(): void
    {
        $html = (string) $this->blade('<x-italia::spid-button :dropdown="true" :providers="[]" />');

        $this->assertStringContainsString('dropdown-menu', $html);
        $this->assertStringContainsString('Poste Italiane', $html);
        $this->assertStringContainsString('Aruba PEC', $html);
        $this->assertStringContainsString('href="/spid/login/poste"', $html);
    }

    public function test_dropdown_uses_default_providers_when_provider_prop_is_omitted(): void
    {
        $html = (string) $this->blade('<x-italia::spid-button dropdown />');

        $this->assertStringContainsString('Poste Italiane', $html);
        $this->assertStringContainsString('Aruba PEC', $html);
    }

    public function test_dropdown_renders_explicit_provider_list_instead_of_defaults(): void
    {
        $providers = [['name' => 'Aruba ID', 'url' => '/spid/login/aruba']];

        $html = (string) $this->blade(
            '<x-italia::spid-button dropdown :providers="$providers" />',
            ['providers' => $providers],
        );

        $this->assertStringContainsString('dropdown-menu', $html);
        $this->assertStringContainsString('Aruba ID', $html);
        $this->assertStringContainsString('href="/spid/login/aruba"', $html);
        $this->assertStringNotContainsString('Poste Italiane', $html);
        $this->assertStringContainsString('data-bs-toggle="dropdown"', $html);
        $this->assertStringContainsString('Maggiori informazioni', $html);
    }

    public function test_no_dropdown_ignores_provided_providers(): void
    {
        $providers = [['name' => 'Custom IdP', 'url' => '/custom-login']];

        $html = (string) $this->blade(
            '<x-italia::spid-button :providers="$providers" />',
            ['providers' => $providers],
        );

        $this->assertStringNotContainsString('dropdown-menu', $html);
        $this->assertStringNotContainsString('Custom IdP', $html);
        $this->assertStringContainsString('<a href="/spid/login"', $html);
    }

    public function test_custom_attributes_passed_through(): void
    {
        $html = (string) $this->blade('<x-italia::spid-button data-test="spid" />');

        $this->assertStringContainsString('data-test="spid"', $html);
    }

    public function test_logo_is_decorative_for_screen_readers(): void
    {
        $html = (string) $this->blade('<x-italia::spid-button />');

        $this->assertStringContainsString('aria-hidden="true"', $html);
        $this->assertStringContainsString('focusable="false"', $html);
    }
}
