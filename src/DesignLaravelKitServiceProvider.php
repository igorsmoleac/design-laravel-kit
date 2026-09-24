<?php

namespace IgorSmoleac\DesignLaravelKit;

use IgorSmoleac\DesignLaravelKit\Commands\InstallCommand;
use IgorSmoleac\DesignLaravelKit\Commands\PublishAssetsCommand;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class DesignLaravelKitServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/design-laravel-kit.php', 'design-laravel-kit');
    }

    public function boot(): void
    {
        $this->registerViews();
        $this->registerPublishing();
        $this->registerCommands();
        $this->registerBladeDirectives();
    }

    protected function registerViews(): void
    {
        $this->loadViewsFrom(
            __DIR__ . '/../resources/views',
            config('design-laravel-kit.views_namespace', 'design-laravel-kit')
        );

        Blade::componentNamespace(
            'IgorSmoleac\\DesignLaravelKit\\Components',
            config('design-laravel-kit.component_prefix', 'italia')
        );
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
            __DIR__ . '/../resources/views' => resource_path(
                'views/vendor/' . config('design-laravel-kit.views_namespace', 'design-laravel-kit')
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

    protected function registerBladeDirectives(): void
    {
        Blade::directive('designLaravelKitStyles', fn () => "<?php echo '<link rel=\"stylesheet\" href=\"' . e(asset(config('design-laravel-kit.assets_path') . '/css/design-laravel-kit.css')) . '\">'; ?>");

        Blade::directive('designLaravelKitScripts', fn () => "<?php echo '<script src=\"' . e(asset(config('design-laravel-kit.assets_path') . '/js/design-laravel-kit.js')) . '\" defer></script>'; ?>");
    }
}
