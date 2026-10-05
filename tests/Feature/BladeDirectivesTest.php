<?php

namespace IgorSmoleac\DesignLaravelKit\Tests\Feature;

use IgorSmoleac\DesignLaravelKit\DesignLaravelKitServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\ViewException;
use Orchestra\Testbench\TestCase;

class BladeDirectivesTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [DesignLaravelKitServiceProvider::class];
    }

    public function test_styles_directive_includes_version_param(): void
    {
        $html = Blade::render('@designLaravelKitStyles' . PHP_EOL . '<!-- ' . uniqid() . ' -->');

        $this->assertStringContainsString('design-laravel-kit.css', $html);
        $this->assertStringContainsString('vendor/design-laravel-kit', $html);
        $this->assertMatchesRegularExpression('/\?v=[^"&]+/', $html);
    }

    public function test_scripts_directive_includes_version_param(): void
    {
        $html = Blade::render('@designLaravelKitScripts' . PHP_EOL . '<!-- ' . uniqid() . ' -->');

        $this->assertStringContainsString('design-laravel-kit.js', $html);
        $this->assertMatchesRegularExpression('/\?v=[^"&]+/', $html);
        $this->assertStringContainsString('defer', $html);
    }

    public function test_asset_version_falls_back_to_dev_in_testbench(): void
    {
        $provider = new class($this->app) extends DesignLaravelKitServiceProvider
        {
            protected function installedAssetVersion(): ?string
            {
                return null;
            }

            public function assetVersion(): string
            {
                return $this->resolveAssetVersion();
            }
        };

        $this->assertSame('dev', $provider->assetVersion());
    }

    public function test_scripts_directive_loads_bootstrap_italia_fonts(): void
    {
        $html = Blade::render('@designLaravelKitScripts' . PHP_EOL);

        $this->assertStringContainsString('DOMContentLoaded', $html);
        $this->assertStringContainsString('window.bootstrap.loadFonts(', $html);
        $this->assertStringContainsString('vendor\\/design-laravel-kit\\/fonts', $html);
    }

    public function test_scripts_directive_renders_no_nonce_attribute_by_default(): void
    {
        $html = Blade::render('@designLaravelKitScripts');

        $this->assertStringContainsString('<script src="', $html);
        $this->assertStringNotContainsString('nonce=', $html);
    }

    public function test_scripts_directive_accepts_nonce_argument(): void
    {
        $html = Blade::render("@designLaravelKitScripts('abc123')");

        $this->assertStringContainsString('<script nonce="abc123" src="', $html);
        $this->assertStringContainsString('<script nonce="abc123">document.addEventListener', $html);
    }

    public function test_scripts_directive_uses_csp_nonce_from_config(): void
    {
        config(['design-laravel-kit.csp_nonce' => 'xyz']);

        $html = Blade::render('@designLaravelKitScripts');

        $this->assertStringContainsString('<script nonce="xyz" src="', $html);
        $this->assertStringContainsString('<script nonce="xyz">document.addEventListener', $html);
    }

    public function test_scripts_directive_argument_overrides_config_nonce(): void
    {
        config(['design-laravel-kit.csp_nonce' => 'from-config']);

        $html = Blade::render("@designLaravelKitScripts('from-argument')");

        $this->assertStringContainsString('nonce="from-argument"', $html);
        $this->assertStringNotContainsString('nonce="from-config"', $html);
    }

    public function test_scripts_directive_rejects_invalid_nonce(): void
    {
        $this->expectException(ViewException::class);
        $this->expectExceptionMessage("Field 'csp_nonce'");

        Blade::render("@designLaravelKitScripts('bad nonce with spaces')");
    }
}
