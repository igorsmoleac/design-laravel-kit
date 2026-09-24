<?php

namespace IgorSmoleac\DesignLaravelKit;

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
        $this->loadViewsFrom(
            __DIR__ . '/../resources/views',
            config('design-laravel-kit.views_namespace', 'design-laravel-kit')
        );

        Blade::componentNamespace(
            'IgorSmoleac\\DesignLaravelKit\\Components',
            config('design-laravel-kit.component_prefix', 'italia')
        );

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/design-laravel-kit.php' => config_path('design-laravel-kit.php'),
            ], 'design-laravel-kit-config');

            $this->publishes([
                __DIR__ . '/../resources/views' => resource_path(
                    'views/vendor/' . config('design-laravel-kit.views_namespace', 'design-laravel-kit')
                ),
            ], 'design-laravel-kit-views');
        }
    }
}
