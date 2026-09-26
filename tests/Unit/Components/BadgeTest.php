<?php

namespace IgorSmoleac\DesignLaravelKit\Tests\Unit\Components;

use IgorSmoleac\DesignLaravelKit\DesignLaravelKitServiceProvider;
use Orchestra\Testbench\TestCase;

class BadgeTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [DesignLaravelKitServiceProvider::class];
    }

    public function test_renders_primary_span_by_default(): void
    {
        $html = (string) $this->blade('<x-italia::badge>Primary</x-italia::badge>');

        $this->assertStringContainsString('<span class="badge bg-primary">Primary</span>', $html);
    }

    public function test_renders_all_variants_with_correct_classes(): void
    {
        foreach (['primary', 'secondary', 'success', 'danger', 'warning', 'info', 'light', 'dark'] as $variant) {
            $html = (string) $this->blade("<x-italia::badge variant=\"{$variant}\">x</x-italia::badge>");

            $this->assertStringContainsString('bg-' . $variant, $html);
        }
    }

    public function test_pill_renders_rounded_pill(): void
    {
        $html = (string) $this->blade('<x-italia::badge pill>x</x-italia::badge>');

        $this->assertStringContainsString('badge rounded-pill bg-primary', $html);
    }

    public function test_no_rounded_pill_by_default(): void
    {
        $html = (string) $this->blade('<x-italia::badge>x</x-italia::badge>');

        $this->assertStringNotContainsString('rounded-pill', $html);
    }

    public function test_href_renders_anchor(): void
    {
        $html = (string) $this->blade('<x-italia::badge href="/foo">Link</x-italia::badge>');

        $this->assertStringContainsString('<a class="badge bg-primary" href="/foo">Link</a>', $html);
    }

    public function test_no_href_renders_span(): void
    {
        $html = (string) $this->blade('<x-italia::badge>x</x-italia::badge>');

        $this->assertStringContainsString('<span', $html);
        $this->assertStringNotContainsString('<a', $html);
    }

    public function test_slot_content_renders(): void
    {
        $html = (string) $this->blade('<x-italia::badge>3 nuovi</x-italia::badge>');

        $this->assertStringContainsString('3 nuovi', $html);
    }

    public function test_custom_class_passed_through(): void
    {
        $html = (string) $this->blade('<x-italia::badge class="ms-1">x</x-italia::badge>');

        $this->assertStringContainsString('badge bg-primary ms-1', $html);
    }

    public function test_id_passed_through_attributes(): void
    {
        $html = (string) $this->blade('<x-italia::badge id="my-badge">x</x-italia::badge>');

        $this->assertStringContainsString('id="my-badge"', $html);
    }

    public function test_aria_label_passed_through_attributes(): void
    {
        $html = (string) $this->blade('<x-italia::badge aria-label="3 nuovi messaggi">3</x-italia::badge>');

        $this->assertStringContainsString('aria-label="3 nuovi messaggi"', $html);
    }
}
