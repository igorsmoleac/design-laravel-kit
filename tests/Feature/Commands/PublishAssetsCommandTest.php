<?php

namespace IgorSmoleac\DesignLaravelKit\Tests\Feature\Commands;

use IgorSmoleac\DesignLaravelKit\DesignLaravelKitServiceProvider;
use Illuminate\Filesystem\Filesystem;
use Orchestra\Testbench\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class PublishAssetsCommandTest extends TestCase
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
        $files->deleteDirectory(public_path('assets/design-laravel-kit'));
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

    public function test_refuses_path_with_parent_directory_traversal(): void
    {
        config()->set('design-laravel-kit.assets_path', '../etc/passwd');

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('contains ".."');

        $this->artisan('design-laravel-kit:publish-assets');
    }

    public function test_refuses_absolute_assets_path(): void
    {
        config()->set('design-laravel-kit.assets_path', '/etc/');

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('absolute path');

        $this->artisan('design-laravel-kit:publish-assets');
    }

    public function test_refuses_path_without_an_allowed_prefix(): void
    {
        config()->set('design-laravel-kit.assets_path', 'custom/path');

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('must start with "vendor/", "assets/" or "build/"');

        $this->artisan('design-laravel-kit:publish-assets', ['--force' => true]);
    }

    public function test_force_works_with_valid_vendor_path(): void
    {
        config()->set('design-laravel-kit.assets_path', 'vendor/design-laravel-kit');

        $this->artisan('design-laravel-kit:publish-assets', ['--force' => true])
            ->assertSuccessful();
    }

    public function test_publishes_assets_to_custom_assets_path(): void
    {
        config()->set('design-laravel-kit.assets_path', 'assets/design-laravel-kit');

        $this->artisan('design-laravel-kit:publish-assets')->assertSuccessful();

        $this->assertFileExists(public_path('assets/design-laravel-kit/css/design-laravel-kit.css'));
    }

    #[DataProvider('acceptedAssetsPaths')]
    public function test_accepts_paths_with_a_package_subdirectory(string $assetsPath, string $publishedPath, string $cleanupPath): void
    {
        config()->set('design-laravel-kit.assets_path', $assetsPath);

        $files = $this->app->make(Filesystem::class);
        $destination = public_path($publishedPath);
        $cleanupDirectory = public_path($cleanupPath);
        $destinationExisted = $files->exists($destination);
        $cleanupDirectoryExisted = $files->exists($cleanupDirectory);

        try {
            $this->artisan('design-laravel-kit:publish-assets')->assertSuccessful();
        } finally {
            if (! $destinationExisted) {
                $files->deleteDirectory($destination);
            }

            if (! $cleanupDirectoryExisted && $cleanupDirectory !== $destination) {
                $files->deleteDirectory($cleanupDirectory);
            }
        }
    }

    /** @return array<string, array{string, string, string}> */
    public static function acceptedAssetsPaths(): array
    {
        return [
            'vendor prefix' => ['vendor/design-laravel-kit', 'vendor/design-laravel-kit', 'vendor/design-laravel-kit'],
            'assets prefix' => ['assets/design-laravel-kit', 'assets/design-laravel-kit', 'assets/design-laravel-kit'],
            'build prefix' => ['build/design-laravel-kit', 'build/design-laravel-kit', 'build/design-laravel-kit'],
            'repeated separators' => ['vendor//design-laravel-kit', 'vendor/design-laravel-kit', 'vendor/design-laravel-kit'],
            'leading dot segment' => ['./vendor/design-laravel-kit', 'vendor/design-laravel-kit', 'vendor/design-laravel-kit'],
            'nested package path' => ['vendor/design-laravel-kit/sub', 'vendor/design-laravel-kit/sub', 'vendor/design-laravel-kit'],
        ];
    }

    #[DataProvider('rejectedAssetsPaths')]
    public function test_rejects_unsafe_assets_paths_with_specific_reasons(string $assetsPath, string $reason): void
    {
        config()->set('design-laravel-kit.assets_path', $assetsPath);

        try {
            $this->artisan('design-laravel-kit:publish-assets', ['--force' => true]);
            $this->fail('Expected the unsafe assets_path to be rejected.');
        } catch (\RuntimeException $exception) {
            $this->assertStringContainsString('assets_path', $exception->getMessage());
            $this->assertStringContainsString("value '{$assetsPath}'", $exception->getMessage());
            $this->assertStringContainsString($reason, $exception->getMessage());
            $this->assertStringContainsString('vendor/<package-name>', $exception->getMessage());
        }
    }

    /** @return array<string, array{string, string}> */
    public static function rejectedAssetsPaths(): array
    {
        return [
            'vendor root with slash' => ['vendor/', 'must include a package subdirectory'],
            'vendor root' => ['vendor', 'must include a package subdirectory'],
            'assets root' => ['assets/', 'must include a package subdirectory'],
            'build root' => ['build/', 'must include a package subdirectory'],
            'empty' => ['', 'is empty'],
            'unix absolute' => ['/etc/passwd', 'absolute path'],
            'windows absolute' => ['C:\\Windows', 'absolute path'],
            'traversal in path' => ['vendor/../etc', 'contains ".."'],
            'traversal at end' => ['vendor/..', 'contains ".."'],
            'traversal at start' => ['../vendor/design-laravel-kit', 'contains ".."'],
            'unapproved prefix' => ['other/design-laravel-kit', 'must start with "vendor/", "assets/" or "build/"'],
        ];
    }

    public function test_force_deletes_only_the_valid_target_and_preserves_neighbor_files(): void
    {
        config()->set('design-laravel-kit.assets_path', 'vendor/design-laravel-kit');

        $files = $this->app->make(Filesystem::class);
        $targetDirectory = public_path('vendor/design-laravel-kit');
        $staleFile = $targetDirectory . '/stale.txt';
        $neighborFile = public_path('vendor/other-package-' . spl_object_id($this) . '.txt');
        $files->makeDirectory(dirname($neighborFile), 0755, true, true);
        $files->makeDirectory($targetDirectory, 0755, true, true);
        $files->put($neighborFile, 'keep');
        $files->put($staleFile, 'remove');

        try {
            $this->artisan('design-laravel-kit:publish-assets', ['--force' => true])->assertSuccessful();

            $this->assertFileExists($neighborFile);
            $this->assertFileDoesNotExist($staleFile);
            $this->assertFileExists($targetDirectory . '/css/design-laravel-kit.css');
        } finally {
            $files->delete($neighborFile);
            $files->deleteDirectory($targetDirectory);
        }
    }

    public function test_force_rejects_vendor_root_without_deleting_neighbor_files(): void
    {
        config()->set('design-laravel-kit.assets_path', 'vendor/');

        $files = $this->app->make(Filesystem::class);
        $neighborFile = public_path('vendor/other-package-' . spl_object_id($this) . '.txt');
        $files->makeDirectory(dirname($neighborFile), 0755, true, true);
        $files->put($neighborFile, 'keep');

        try {
            $this->artisan('design-laravel-kit:publish-assets', ['--force' => true]);
            $this->fail('Expected the vendor root to be rejected before deletion.');
        } catch (\RuntimeException $exception) {
            $this->assertStringContainsString('must include a package subdirectory', $exception->getMessage());
            $this->assertFileExists($neighborFile);
        } finally {
            $files->delete($neighborFile);
        }
    }
}
