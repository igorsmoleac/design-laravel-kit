<?php

namespace IgorSmoleac\DesignLaravelKit\Tests\Feature\Commands;

use IgorSmoleac\DesignLaravelKit\DesignLaravelKitServiceProvider;
use Illuminate\Filesystem\Filesystem;
use Orchestra\Testbench\TestCase;

class PublishAssetsCommandTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [DesignLaravelKitServiceProvider::class];
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->app->make(Filesystem::class)->deleteDirectory(public_path('vendor/design-laravel-kit'));
    }

    public function test_publishes_assets_to_public_directory(): void
    {
        $this->artisan('design-laravel-kit:publish-assets')->assertSuccessful();

        $this->assertFileExists(public_path('vendor/design-laravel-kit/css/design-laravel-kit.css'));
        $this->assertFileExists(public_path('vendor/design-laravel-kit/js/design-laravel-kit.js'));
        $this->assertFileExists(public_path('vendor/design-laravel-kit/svg/sprites.svg'));
    }

    public function test_fails_when_dist_is_missing(): void
    {
        $dist = realpath(__DIR__ . '/../../../resources/dist');
        $backup = $dist . '_backup';

        rename($dist, $backup);

        try {
            $this->artisan('design-laravel-kit:publish-assets')
                ->expectsOutput('Assets not built. Run: npm install && npm run build')
                ->assertFailed();
        } finally {
            rename($backup, $dist);
        }
    }

    public function test_does_not_overwrite_without_force(): void
    {
        $this->artisan('design-laravel-kit:publish-assets')->assertSuccessful();

        $published = public_path('vendor/design-laravel-kit/css/design-laravel-kit.css');
        file_put_contents($published, 'modified');

        $this->artisan('design-laravel-kit:publish-assets')
            ->expectsOutput('Assets already published. Use --force to overwrite.')
            ->assertSuccessful();

        $this->assertSame('modified', file_get_contents($published));
    }

    public function test_overwrites_with_force(): void
    {
        $this->artisan('design-laravel-kit:publish-assets')->assertSuccessful();

        $published = public_path('vendor/design-laravel-kit/css/design-laravel-kit.css');
        file_put_contents($published, 'modified');

        $this->artisan('design-laravel-kit:publish-assets', ['--force' => true])->assertSuccessful();

        $this->assertNotSame('modified', file_get_contents($published));
    }

    public function test_force_removes_stale_files(): void
    {
        $this->artisan('design-laravel-kit:publish-assets')->assertSuccessful();

        $stale = public_path('vendor/design-laravel-kit/fonts/legacy.woff');
        file_put_contents($stale, 'stale');

        $this->artisan('design-laravel-kit:publish-assets', ['--force' => true])->assertSuccessful();

        $this->assertFileDoesNotExist($stale);
    }

    public function test_force_refuses_to_delete_public_root_when_assets_path_empty(): void
    {
        config()->set('design-laravel-kit.assets_path', '');

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('assets_path is empty');

        $this->artisan('design-laravel-kit:publish-assets', ['--force' => true]);
    }

    public function test_force_refuses_path_with_parent_directory_traversal(): void
    {
        config()->set('design-laravel-kit.assets_path', 'vendor/../../etc');

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('contains ".."');

        $this->artisan('design-laravel-kit:publish-assets', ['--force' => true]);
    }

    public function test_force_refuses_path_not_starting_with_vendor(): void
    {
        config()->set('design-laravel-kit.assets_path', 'custom/path');

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('must start with "vendor/"');

        $this->artisan('design-laravel-kit:publish-assets', ['--force' => true]);
    }

    public function test_force_works_with_valid_vendor_path(): void
    {
        config()->set('design-laravel-kit.assets_path', 'vendor/design-laravel-kit');

        $this->artisan('design-laravel-kit:publish-assets', ['--force' => true])
            ->assertSuccessful();
    }
}
