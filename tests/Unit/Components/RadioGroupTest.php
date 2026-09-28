<?php

namespace IgorSmoleac\DesignLaravelKit\Tests\Unit\Components;

use IgorSmoleac\DesignLaravelKit\DesignLaravelKitServiceProvider;
use Orchestra\Testbench\TestCase;

class RadioGroupTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [DesignLaravelKitServiceProvider::class];
    }

    public function test_renders_fieldset_and_legend(): void
    {
        $html = (string) $this->blade('<x-italia::radio-group name="gender" legend="Genere" />');

        $this->assertStringContainsString('<fieldset', $html);
        $this->assertMatchesRegularExpression('/<legend>\s*Genere\s*<\/legend>/s', $html);
    }

    public function test_hint_renders_with_id(): void
    {
        $html = (string) $this->blade('<x-italia::radio-group name="gender" hint="Scegli un genere" />');

        $this->assertMatchesRegularExpression('/<small class="form-text" id="dlk-gender-\d+-hint">Scegli un genere<\/small>/', $html);
    }

    public function test_required_renders_visible_and_screen_reader_markers(): void
    {
        $html = (string) $this->blade('<x-italia::radio-group name="gender" legend="Genere" required />');

        $this->assertStringContainsString('<span class="text-danger" aria-hidden="true">*</span>', $html);
        $this->assertStringContainsString('<span class="visually-hidden">(obbligatorio)</span>', $html);
    }

    public function test_group_error_is_rendered_once(): void
    {
        $this->withViewErrors(['gender' => 'Seleziona un genere.']);

        $html = (string) $this->blade('<x-italia::radio-group name="gender"><x-italia::radio name="gender" value="m" /><x-italia::radio name="gender" value="f" /><x-italia::radio name="gender" value="o" /></x-italia::radio-group>');

        $this->assertSame(1, substr_count($html, 'role="alert"'));
        $this->assertSame(1, substr_count($html, 'Seleziona un genere.'));
    }

    public function test_child_radios_do_not_render_group_error(): void
    {
        $this->withViewErrors(['gender' => 'Seleziona un genere.']);

        $html = (string) $this->blade('<x-italia::radio-group name="gender"><x-italia::radio name="gender" value="m" /><x-italia::radio name="gender" value="f" /></x-italia::radio-group>');

        $this->assertStringNotContainsString('class="invalid-feedback"', $html);
        $this->assertStringContainsString('class="invalid-feedback d-block"', $html);
    }

    public function test_child_radios_render_inside_fieldset(): void
    {
        $html = (string) $this->blade('<x-italia::radio-group name="gender"><x-italia::radio name="gender" value="m" label="Maschio" /></x-italia::radio-group>');

        $this->assertMatchesRegularExpression('/<fieldset[^>]*>.*type="radio".*<\/fieldset>/s', $html);
    }

    public function test_legend_defaults_to_headline_of_name(): void
    {
        $html = (string) $this->blade('<x-italia::radio-group name="gender_identity" />');

        $this->assertMatchesRegularExpression('/<legend>\s*Gender Identity\s*<\/legend>/s', $html);
    }

    public function test_empty_hint_is_not_rendered(): void
    {
        $html = (string) $this->blade('<x-italia::radio-group name="gender" hint="" />');

        $this->assertStringNotContainsString('form-text', $html);
    }

    public function test_error_bag_selects_group_validation_message(): void
    {
        $this->withViewErrors(['gender' => 'Custom bag error.'], 'custom');

        $html = (string) $this->blade('<x-italia::radio-group name="gender" error-bag="custom" />');

        $this->assertStringContainsString('Custom bag error.', $html);
    }
}
