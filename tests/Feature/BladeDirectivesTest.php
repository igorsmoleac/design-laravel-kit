<?php

namespace IgorSmoleac\DesignLaravelKit\Tests\Feature;

use IgorSmoleac\DesignLaravelKit\DesignLaravelKitServiceProvider;
use Illuminate\Support\Facades\Blade;
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
        $this->assertMatchesRegularExpression('/\?v=[^\"]+/', $html);
    }

    public function test_scripts_directive_includes_version_param(): void
    {
        $html = Blade::render('@designLaravelKitScripts' . PHP_EOL . '<!-- ' . uniqid() . ' -->');

        $this->assertStringContainsString('design-laravel-kit.js', $html);
        $this->assertMatchesRegularExpression('/\?v=[^\"]+/', $html);
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
}
