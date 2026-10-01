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

    public function test_optional_field_has_no_marker(): void
    {
        $html = (string) $this->blade('<x-italia::radio-group name="gender" legend="Genere" />');

        $this->assertStringNotContainsString('text-danger', $html);
        $this->assertStringNotContainsString('(obbligatorio)', $html);
    }

    public function test_group_error_is_rendered_once(): void
    {
        $this->withViewErrors(['gender' => 'Seleziona un genere.']);

        $html = (string) $this->blade('<x-italia::radio-group name="gender"><x-italia::radio name="gender" value="m" /><x-italia::radio name="gender" value="f" /><x-italia::radio name="gender" value="o" /></x-italia::radio-group>');

        $this->assertSame(1, substr_count($html, 'role="alert"'));
        $this->assertSame(1, substr_count($html, 'Seleziona un genere.'));
    }

    public function test_fieldset_describedby_includes_hint_and_error_ids(): void
    {
        $this->withViewErrors(['gender' => 'Required']);

        $html = (string) $this->blade('<x-italia::radio-group name="gender" hint="Choose one"><x-italia::radio name="gender" value="m" /></x-italia::radio-group>');

        preg_match('/<fieldset[^>]*aria-describedby="([^"]+)"/', $html, $describedBy);
        preg_match('/<small class="form-text" id="([^"]+-hint)"/', $html, $hintId);
        preg_match('/<div class="invalid-feedback d-block" id="([^"]+-error)"/', $html, $errorId);

        $this->assertNotEmpty($describedBy);
        $this->assertNotEmpty($hintId);
        $this->assertNotEmpty($errorId);
        $this->assertSame($hintId[1] . ' ' . $errorId[1], $describedBy[1]);
    }

    public function test_child_radios_include_group_error_id_in_aria_describedby(): void
    {
        $this->withViewErrors(['gender' => 'Required']);

        $html = (string) $this->blade('<x-italia::radio-group name="gender"><x-italia::radio name="gender" value="m" /></x-italia::radio-group>');

        preg_match('/<input[^>]*aria-describedby="([^"]+)"[^>]*>/s', $html, $describedBy);
        preg_match('/<div class="invalid-feedback d-block" id="([^"]+-error)"/', $html, $errorId);

        $this->assertNotEmpty($describedBy);
        $this->assertNotEmpty($errorId);
        $this->assertSame($errorId[1], $describedBy[1]);
    }

    public function test_child_with_hint_includes_both_error_and_hint_ids(): void
    {
        $this->withViewErrors(['gender' => 'Required']);

        $html = (string) $this->blade('<x-italia::radio-group name="gender"><x-italia::radio name="gender" value="m" hint="Choose one" /></x-italia::radio-group>');

        preg_match('/<input[^>]*aria-describedby="([^"]+)"[^>]*>/s', $html, $describedBy);
        preg_match('/<small class="form-text" id="([^"]+-hint)"/', $html, $hintId);
        preg_match('/<div class="invalid-feedback d-block" id="([^"]+-error)"/', $html, $errorId);

        $this->assertNotEmpty($describedBy);
        $this->assertNotEmpty($hintId);
        $this->assertNotEmpty($errorId);
        $this->assertSame($errorId[1] . ' ' . $hintId[1], $describedBy[1]);
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

    public function test_bag_selects_group_validation_message(): void
    {
        $this->withViewErrors(['gender' => 'Custom bag error.'], 'custom');

        $html = (string) $this->blade('<x-italia::radio-group name="gender" bag="custom" />');

        $this->assertStringContainsString('Custom bag error.', $html);
    }
}
