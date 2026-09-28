<?php

namespace IgorSmoleac\DesignLaravelKit\Tests\Unit\Components;

use IgorSmoleac\DesignLaravelKit\DesignLaravelKitServiceProvider;
use Orchestra\Testbench\TestCase;

class SpinnerTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [DesignLaravelKitServiceProvider::class];
    }

    public function test_renders_progress_spinner_by_default(): void
    {
        $html = (string) $this->blade('<x-italia::spinner />');

        $this->assertStringContainsString('progress-spinner', $html);
        $this->assertStringContainsString('size-md', $html);
    }

    public function test_animation_class_is_always_present(): void
    {
        $html = (string) $this->blade('<x-italia::spinner />');

        $this->assertStringContainsString('progress-spinner-active', $html);
    }

    public function test_size_sm_renders_size_class(): void
    {
        $html = (string) $this->blade('<x-italia::spinner size="sm" />');

        $this->assertStringContainsString('size-sm', $html);
    }

    public function test_size_lg_renders_size_class(): void
    {
        $html = (string) $this->blade('<x-italia::spinner size="lg" />');

        $this->assertStringContainsString('size-lg', $html);
    }

    public function test_size_xl_renders_size_class(): void
    {
        $html = (string) $this->blade('<x-italia::spinner size="xl" />');

        $this->assertStringContainsString('size-xl', $html);
    }

    public function test_spinner_has_role_status_by_default(): void
    {
        $html = (string) $this->blade('<x-italia::spinner />');

        $this->assertStringContainsString('role="status"', $html);
    }

    public function test_spinner_has_default_aria_label(): void
    {
        $html = (string) $this->blade('<x-italia::spinner />');

        $this->assertStringContainsString('aria-label="Caricamento in corso"', $html);
    }

    public function test_custom_label_overrides_default(): void
    {
        $html = (string) $this->blade('<x-italia::spinner label="Salvataggio in corso" />');

        $this->assertStringContainsString('aria-label="Salvataggio in corso"', $html);
        $this->assertStringNotContainsString('aria-label="Caricamento in corso"', $html);
    }

    public function test_decorative_spinner_has_aria_hidden_and_no_role(): void
    {
        $html = (string) $this->blade('<x-italia::spinner :decorative="true" />');

        $this->assertStringContainsString('aria-hidden="true"', $html);
        $this->assertStringNotContainsString('role="status"', $html);
        $this->assertStringNotContainsString('aria-label', $html);
    }

    public function test_double_renders_two_block_inner_elements(): void
    {
        $html = (string) $this->blade('<x-italia::spinner />');

        $this->assertStringContainsString('progress-spinner-double', $html);
        $this->assertSame(2, substr_count($html, '<div class="progress-spinner-inner"></div>'));
    }

    public function test_single_spinner_has_no_inner_spans(): void
    {
        $html = (string) $this->blade('<x-italia::spinner :double="false" />');

        $this->assertStringNotContainsString('progress-spinner-double', $html);
        $this->assertStringNotContainsString('progress-spinner-inner', $html);
    }

    public function test_custom_class_passed_through(): void
    {
        $html = (string) $this->blade('<x-italia::spinner class="my-3" />');

        $this->assertStringContainsString('progress-spinner-active progress-spinner-double size-md my-3', $html);
    }
}
