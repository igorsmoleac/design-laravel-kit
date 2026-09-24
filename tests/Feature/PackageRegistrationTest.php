<?php

namespace IgorSmoleac\DesignLaravelKit\Tests\Feature;

use IgorSmoleac\DesignLaravelKit\DesignLaravelKitServiceProvider;
use Orchestra\Testbench\TestCase;

class PackageRegistrationTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [DesignLaravelKitServiceProvider::class];
    }

    public function test_config_is_merged(): void
    {
        $this->assertSame('design-laravel-kit', config('design-laravel-kit.views_namespace'));
        $this->assertSame('italia', config('design-laravel-kit.component_prefix'));
        $this->assertSame('dlk', config('design-laravel-kit.id_prefix'));
        $this->assertSame('vendor/design-laravel-kit', config('design-laravel-kit.assets_path'));
    }

    public function test_view_namespace_is_registered(): void
    {
        $hints = $this->app['view']->getFinder()->getHints();

        $this->assertArrayHasKey('design-laravel-kit', $hints);
        $this->assertNotEmpty($hints['design-laravel-kit']);
    }
}
