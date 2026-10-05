<?php

namespace IgorSmoleac\DesignLaravelKit\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class PublishAssetsCommand extends Command
{
    protected $signature = 'design-laravel-kit:publish-assets {--force : Overwrite already published assets}';

    protected $description = 'Publish Bootstrap Italia assets (CSS, JS) to the public directory';

    public function __construct(protected Filesystem $files)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $source = __DIR__ . '/../../resources/dist';

        if (! $this->files->isDirectory($source)) {
            $this->error('Assets not built. Run: npm install && npm run build');

            return Command::FAILURE;
        }

        $assetsPath = config('design-laravel-kit.assets_path', 'vendor/design-laravel-kit');
        $this->assertSafeAssetsPath($assetsPath);
        $destination = public_path($assetsPath);

        if ($this->files->exists($destination) && ! $this->option('force')) {
            $this->warn('Assets already published. Use --force to overwrite.');

            return Command::SUCCESS;
        }

        if ($this->option('force')) {
            $this->files->deleteDirectory($destination);
        }

        $this->files->copyDirectory($source, $destination);

        $this->info('Assets published to public/' . $assetsPath);

        return Command::SUCCESS;
    }

    protected function assertSafeAssetsPath(string $assetsPath): void
    {
        if ($assetsPath === '') {
            $this->refuseUnsafeAssetsPath($assetsPath, 'is empty');
        }

        if (
            str_starts_with($assetsPath, '/')
            || str_starts_with($assetsPath, '\\')
            || preg_match('/^[A-Za-z]:/', $assetsPath) === 1
        ) {
            $this->refuseUnsafeAssetsPath($assetsPath, 'must not be an absolute path');
        }

        if (str_contains($assetsPath, '..')) {
            $this->refuseUnsafeAssetsPath($assetsPath, 'contains ".." (path traversal)');
        }

        $pathSegments = array_values(array_filter(
            explode('/', trim($assetsPath, '/')),
            static fn (string $pathSegment): bool => $pathSegment !== '' && $pathSegment !== '.',
        ));

        if ($pathSegments === []) {
            $this->refuseUnsafeAssetsPath($assetsPath, 'is empty after normalization');
        }

        if (! in_array($pathSegments[0], ['vendor', 'assets', 'build'], true)) {
            $this->refuseUnsafeAssetsPath($assetsPath, 'must start with "vendor/", "assets/" or "build/"');
        }

        if (! isset($pathSegments[1])) {
            $this->refuseUnsafeAssetsPath($assetsPath, 'must include a package subdirectory after the prefix');
        }
    }

    private function refuseUnsafeAssetsPath(string $assetsPath, string $reason): never
    {
        throw new \RuntimeException(
            "Refusing to publish assets: assets_path {$reason}; value '{$assetsPath}'. Allowed example: 'vendor/<package-name>'."
        );
    }
}
