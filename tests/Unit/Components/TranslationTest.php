<?php

namespace IgorSmoleac\DesignLaravelKit\Tests\Unit\Components;

use IgorSmoleac\DesignLaravelKit\DesignLaravelKitServiceProvider;
use Orchestra\Testbench\TestCase;

class TranslationTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [DesignLaravelKitServiceProvider::class];
    }

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('app.locale', 'it');
    }

    public function test_modal_close_label_is_translatable(): void
    {
        app()->setLocale('en');

        $html = (string) $this->blade('<x-italia::modal title="Title">Content</x-italia::modal>');

        $this->assertStringContainsString('aria-label="Close modal window"', $html);
    }

    public function test_alert_close_label_is_translatable(): void
    {
        app()->setLocale('en');

        $html = (string) $this->blade('<x-italia::alert dismissible>Alert</x-italia::alert>');

        $this->assertStringContainsString('aria-label="Close"', $html);
    }

    public function test_required_marker_is_translatable(): void
    {
        app()->setLocale('en');

        $html = (string) $this->blade('<x-italia::input name="email" required />');

        $this->assertStringContainsString('(required)', $html);
    }

    public function test_default_locale_renders_italian(): void
    {
        $this->assertSame('it', app()->getLocale());

        $html = (string) $this->blade('<x-italia::input name="email" required />');

        $this->assertStringContainsString('(obbligatorio)', $html);
    }
}
