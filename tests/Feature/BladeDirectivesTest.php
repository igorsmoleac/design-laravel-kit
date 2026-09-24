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

    public function test_styles_directive_renders_link_tag(): void
    {
        $html = Blade::render('@designLaravelKitStyles');

        $this->assertStringContainsString('design-laravel-kit.css', $html);
        $this->assertStringContainsString('vendor/design-laravel-kit', $html);
    }

    public function test_scripts_directive_renders_script_tag_with_defer(): void
    {
        $html = Blade::render('@designLaravelKitScripts');

        $this->assertStringContainsString('design-laravel-kit.js', $html);
        $this->assertStringContainsString('defer', $html);
    }
}
