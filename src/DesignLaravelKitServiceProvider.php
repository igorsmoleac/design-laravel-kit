<?php

namespace IgorSmoleac\DesignLaravelKit;

use Composer\InstalledVersions;
use IgorSmoleac\DesignLaravelKit\Commands\InstallCommand;
use IgorSmoleac\DesignLaravelKit\Commands\PublishAssetsCommand;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class DesignLaravelKitServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/design-laravel-kit.php', 'design-laravel-kit');
        config(['design-laravel-kit.version' => $this->resolveAssetVersion()]);
    }

    public function boot(): void
    {
        $this->registerViews();
        $this->registerTranslations();
        $this->registerPublishing();
        $this->registerCommands();
        $this->registerBladeDirectives();
    }

    protected function registerTranslations(): void
    {
        $this->loadTranslationsFrom(__DIR__ . '/../lang', 'design-laravel-kit');
        $this->loadJsonTranslationsFrom(__DIR__ . '/../lang');
        $this->loadJsonTranslationsFrom(lang_path('vendor/design-laravel-kit'));
    }

    protected function registerViews(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'design-laravel-kit');

        Blade::componentNamespace('IgorSmoleac\\DesignLaravelKit\\Components', 'italia');
    }

    protected function registerPublishing(): void
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->publishes([
            __DIR__ . '/../config/design-laravel-kit.php' => config_path('design-laravel-kit.php'),
        ], 'design-laravel-kit-config');

        $this->publishes([
            __DIR__ . '/../lang' => lang_path('vendor/design-laravel-kit'),
        ], 'design-laravel-kit-lang');

        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path(
                'views/vendor/design-laravel-kit'
            ),
        ], 'design-laravel-kit-views');

        $this->publishes([
            __DIR__ . '/../resources/dist' => public_path(config('design-laravel-kit.assets_path', 'vendor/design-laravel-kit')),
        ], 'design-laravel-kit-assets');
    }

    protected function registerCommands(): void
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->commands([
            PublishAssetsCommand::class,
            InstallCommand::class,
        ]);
    }

    protected function resolveAssetVersion(): string
    {
        if (! class_exists(InstalledVersions::class)) {
            return 'dev';
        }

        try {
            return $this->installedAssetVersion() ?? 'dev';
        } catch (\OutOfRangeException) {
            return 'dev';
        }
    }

    protected function installedAssetVersion(): ?string
    {
        return InstalledVersions::getPrettyVersion('igorsmoleac/design-laravel-kit');
    }

    protected function registerBladeDirectives(): void
    {
        Blade::directive('designLaravelKitStyles', fn () => <<<'PHP'
<?php
$designLaravelKitAssetsPath = config('design-laravel-kit.assets_path');
$designLaravelKitVersion = config('design-laravel-kit.version');
echo '<link rel="stylesheet" href="' . e(asset($designLaravelKitAssetsPath . '/css/design-laravel-kit.css') . '?v=' . $designLaravelKitVersion) . '">';
?>
PHP
        );

        Blade::directive('designLaravelKitScripts', function ($expression) {
            $nonceExpression = trim((string) $expression) !== '' ? "({$expression})" : '(null)';

            return str_replace(
                '__DLK_NONCE_EXPRESSION__',
                $nonceExpression,
                <<<'PHP'
<?php
$designLaravelKitAssetsPath = config('design-laravel-kit.assets_path');
$designLaravelKitVersion = config('design-laravel-kit.version');
$designLaravelKitNonce = \IgorSmoleac\DesignLaravelKit\DTO\ConfigValidator::cspNonce(__DLK_NONCE_EXPRESSION__, config('design-laravel-kit.csp_nonce'));
$designLaravelKitNonceAttribute = $designLaravelKitNonce === null ? '' : ' nonce="' . $designLaravelKitNonce . '"';
echo '<script' . $designLaravelKitNonceAttribute . ' src="' . e(asset($designLaravelKitAssetsPath . '/js/design-laravel-kit.js') . '?v=' . $designLaravelKitVersion) . '" defer></script>';
echo '<script' . $designLaravelKitNonceAttribute . '>document.addEventListener("DOMContentLoaded", function () { if (window.bootstrap && window.bootstrap.loadFonts) { window.bootstrap.loadFonts(' . \Illuminate\Support\Js::from(asset($designLaravelKitAssetsPath . '/fonts')) . '); } });</script>';
?>
PHP
            );
        });
    }
}
