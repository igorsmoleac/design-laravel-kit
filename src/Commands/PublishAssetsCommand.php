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
        $destination = public_path($assetsPath);

        if ($this->files->exists($destination) && ! $this->option('force')) {
            $this->warn('Assets already published. Use --force to overwrite.');

            return Command::SUCCESS;
        }

        if ($this->option('force')) {
            $this->assertSafeAssetsPath($assetsPath);
            $this->files->deleteDirectory($destination);
        }

        $this->files->copyDirectory($source, $destination);

        $this->info('Assets published to public/' . $assetsPath);

        return Command::SUCCESS;
    }

    protected function assertSafeAssetsPath(string $assetsPath): void
    {
        $normalized = trim($assetsPath, '/');

        if ($normalized === '') {
            throw new \RuntimeException(
                'Refusing to delete public path: assets_path is empty.'
            );
        }

        if (str_contains($normalized, '..')) {
            throw new \RuntimeException(
                'Refusing to delete public path: assets_path contains "..".'
            );
        }

        if (! str_starts_with($normalized, 'vendor/')) {
            throw new \RuntimeException(
                'Refusing to delete public path: assets_path must start with "vendor/".'
            );
        }
    }
}
