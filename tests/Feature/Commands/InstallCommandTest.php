<?php

namespace IgorSmoleac\DesignLaravelKit\Tests\Feature\Commands;

use IgorSmoleac\DesignLaravelKit\DesignLaravelKitServiceProvider;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Artisan;
use Orchestra\Testbench\TestCase;

class InstallCommandTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [DesignLaravelKitServiceProvider::class];
    }

    protected function setUp(): void
    {
        parent::setUp();

        $files = $this->app->make(Filesystem::class);
        $files->deleteDirectory(public_path('vendor/design-laravel-kit'));
        $files->delete(config_path('design-laravel-kit.php'));
    }

    public function test_install_command_is_registered(): void
    {
        $this->assertArrayHasKey('design-laravel-kit:install', Artisan::all());
        $this->assertArrayHasKey('design-laravel-kit:publish-assets', Artisan::all());
    }

    public function test_install_runs_publish_and_assets(): void
    {
        $this->artisan('design-laravel-kit:install')
            ->expectsOutput(' - Add @designLaravelKitStyles to <head>')
            ->expectsOutput(' - Add @designLaravelKitScripts before </body>')
            ->assertSuccessful();

        $this->assertFileExists(config_path('design-laravel-kit.php'));
        $this->assertFileExists(public_path('vendor/design-laravel-kit/css/design-laravel-kit.css'));
        $this->assertFileExists(public_path('vendor/design-laravel-kit/js/design-laravel-kit.js'));
    }
}
