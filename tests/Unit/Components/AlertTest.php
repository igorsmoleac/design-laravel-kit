<?php

namespace IgorSmoleac\DesignLaravelKit\Tests\Unit\Components;

use IgorSmoleac\DesignLaravelKit\DesignLaravelKitServiceProvider;
use Orchestra\Testbench\TestCase;

class AlertTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [DesignLaravelKitServiceProvider::class];
    }

    public function test_renders_info_alert_by_default(): void
    {
        $html = (string) $this->blade('<x-italia::alert>Testo</x-italia::alert>');

        $this->assertStringContainsString('alert alert-info', $html);
        $this->assertStringContainsString('role="alert"', $html);
    }

    public function test_renders_all_variants_with_correct_classes(): void
    {
        $info = (string) $this->blade('<x-italia::alert variant="info">x</x-italia::alert>');
        $success = (string) $this->blade('<x-italia::alert variant="success">x</x-italia::alert>');
        $warning = (string) $this->blade('<x-italia::alert variant="warning">x</x-italia::alert>');
        $danger = (string) $this->blade('<x-italia::alert variant="danger">x</x-italia::alert>');

        $this->assertStringContainsString('alert-info', $info);
        $this->assertStringContainsString('alert-success', $success);
        $this->assertStringContainsString('alert-warning', $warning);
        $this->assertStringContainsString('alert-danger', $danger);
    }

    public function test_unknown_variant_falls_back_to_info(): void
    {
        $html = (string) $this->blade('<x-italia::alert variant="purple">x</x-italia::alert>');

        $this->assertStringContainsString('alert-info', $html);
        $this->assertStringNotContainsString('alert-purple', $html);
    }

    public function test_title_renders_in_alert_heading(): void
    {
        $html = (string) $this->blade('<x-italia::alert title="Errore">x</x-italia::alert>');

        $this->assertStringContainsString('<h4 class="alert-heading">Errore</h4>', $html);
    }

    public function test_no_heading_without_title(): void
    {
        $html = (string) $this->blade('<x-italia::alert>x</x-italia::alert>');

        $this->assertStringNotContainsString('alert-heading', $html);
        $this->assertStringNotContainsString('<h4', $html);
    }

    public function test_dismissible_renders_close_button(): void
    {
        $html = (string) $this->blade('<x-italia::alert dismissible>x</x-italia::alert>');

        $this->assertStringContainsString('alert-dismissible', $html);
        $this->assertStringContainsString('data-bs-dismiss="alert"', $html);
        $this->assertStringContainsString('aria-label="Chiudi"', $html);
    }

    public function test_dismissible_adds_fade_and_show_classes(): void
    {
        $html = (string) $this->blade('<x-italia::alert dismissible>x</x-italia::alert>');

        $this->assertStringContainsString('fade show', $html);
    }

    public function test_no_close_button_without_dismissible(): void
    {
        $html = (string) $this->blade('<x-italia::alert>x</x-italia::alert>');

        $this->assertStringNotContainsString('btn-close', $html);
        $this->assertStringNotContainsString('data-bs-dismiss', $html);
    }

    public function test_icon_rendered_when_enabled(): void
    {
        $html = (string) $this->blade('<x-italia::alert variant="info" :icon="true">x</x-italia::alert>');

        $this->assertStringContainsString('<svg', $html);
        $this->assertStringContainsString('#it-info-circle', $html);
    }

    public function test_icon_not_rendered_by_default(): void
    {
        $html = (string) $this->blade('<x-italia::alert variant="info">x</x-italia::alert>');

        $this->assertStringNotContainsString('<svg', $html);
    }

    public function test_icon_matches_variant(): void
    {
        $success = (string) $this->blade('<x-italia::alert variant="success" :icon="true">x</x-italia::alert>');
        $warning = (string) $this->blade('<x-italia::alert variant="warning" :icon="true">x</x-italia::alert>');
        $danger = (string) $this->blade('<x-italia::alert variant="danger" :icon="true">x</x-italia::alert>');

        $this->assertStringContainsString('#it-check-circle', $success);
        $this->assertStringContainsString('#it-warning', $warning);
        $this->assertStringContainsString('#it-error', $danger);
    }

    public function test_no_icon_when_disabled(): void
    {
        $html = (string) $this->blade('<x-italia::alert :icon="false">x</x-italia::alert>');

        $this->assertStringNotContainsString('<svg', $html);
    }

    public function test_slot_content_renders(): void
    {
        $html = (string) $this->blade('<x-italia::alert>Qualcosa è andato storto.</x-italia::alert>');

        $this->assertStringContainsString('Qualcosa è andato storto.', $html);
    }

    public function test_custom_class_passed_through(): void
    {
        $html = (string) $this->blade('<x-italia::alert class="mb-2">x</x-italia::alert>');

        $this->assertStringContainsString('alert alert-info mb-2', $html);
    }

    public function test_dismissible_has_close_button_without_inner_icon(): void
    {
        $html = (string) $this->blade('<x-italia::alert dismissible>x</x-italia::alert>');

        $this->assertStringContainsString('btn-close', $html);
        $this->assertStringContainsString('data-bs-dismiss="alert"', $html);
        $this->assertStringNotContainsString('#it-close', $html);
    }
}
