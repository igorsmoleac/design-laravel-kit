<?php

namespace IgorSmoleac\DesignLaravelKit\Tests\Feature;

use IgorSmoleac\DesignLaravelKit\DesignLaravelKitServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Vite;
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

    public function test_directives_use_vite_csp_nonce(): void
    {
        Vite::useCspNonce('xyz');

        $styles = Blade::render('@designLaravelKitStyles');
        $scripts = Blade::render('@designLaravelKitScripts');

        $this->assertStringContainsString('<link nonce="xyz" rel="stylesheet"', $styles);
        $this->assertStringContainsString('<script nonce="xyz" src="', $scripts);
        $this->assertStringContainsString('<script nonce="xyz">document.addEventListener', $scripts);
    }

    public function test_scripts_directive_argument_overrides_vite_nonce(): void
    {
        Vite::useCspNonce('from-vite');

        $html = Blade::render("@designLaravelKitScripts('from-argument')");

        $this->assertStringContainsString('nonce="from-argument"', $html);
        $this->assertStringNotContainsString('nonce="from-vite"', $html);
    }

    public function test_scripts_directive_rejects_invalid_nonce(): void
    {
        $this->expectException(ViewException::class);
        $this->expectExceptionMessage("Field 'csp_nonce'");

        Blade::render("@designLaravelKitScripts('bad nonce with spaces')");
    }

    public function test_styles_directive_accepts_nonce_argument(): void
    {
        $html = Blade::render("@designLaravelKitStyles('abc123')");

        $this->assertStringContainsString('<link nonce="abc123" rel="stylesheet"', $html);
    }
}
