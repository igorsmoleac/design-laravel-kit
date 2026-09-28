<?php

namespace IgorSmoleac\DesignLaravelKit\Tests\Unit\Components;

use IgorSmoleac\DesignLaravelKit\DesignLaravelKitServiceProvider;
use Orchestra\Testbench\TestCase;

class CheckboxGroupTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [DesignLaravelKitServiceProvider::class];
    }

    public function test_renders_fieldset_and_legend(): void
    {
        $html = (string) $this->blade('<x-italia::checkbox-group name="interests" legend="Interessi" />');

        $this->assertStringContainsString('<fieldset', $html);
        $this->assertMatchesRegularExpression('/<legend>\s*Interessi\s*<\/legend>/s', $html);
    }

    public function test_hint_renders_with_id(): void
    {
        $html = (string) $this->blade('<x-italia::checkbox-group name="interests" hint="Scegli uno o più interessi" />');

        $this->assertMatchesRegularExpression('/<small class="form-text" id="dlk-interests-\d+-hint">Scegli uno o più interessi<\/small>/', $html);
    }

    public function test_required_renders_visible_and_screen_reader_markers(): void
    {
        $html = (string) $this->blade('<x-italia::checkbox-group name="interests" legend="Interessi" required />');

        $this->assertStringContainsString('<span class="text-danger" aria-hidden="true">*</span>', $html);
        $this->assertStringContainsString('<span class="visually-hidden">(obbligatorio)</span>', $html);
    }

    public function test_optional_field_has_no_marker(): void
    {
        $html = (string) $this->blade('<x-italia::checkbox-group name="interests" legend="Interessi" />');

        $this->assertStringNotContainsString('text-danger', $html);
        $this->assertStringNotContainsString('(obbligatorio)', $html);
    }

    public function test_group_error_is_rendered_once(): void
    {
        $this->withViewErrors(['interests' => 'Seleziona almeno un interesse.']);

        $html = (string) $this->blade('<x-italia::checkbox-group name="interests"><x-italia::checkbox name="interests[]" value="sport" /><x-italia::checkbox name="interests[]" value="music" /></x-italia::checkbox-group>');

        $this->assertSame(1, substr_count($html, 'role="alert"'));
        $this->assertSame(1, substr_count($html, 'Seleziona almeno un interesse.'));
    }

    public function test_fieldset_describedby_includes_hint_and_error_ids(): void
    {
        $this->withViewErrors(['interests' => 'Required']);

        $html = (string) $this->blade('<x-italia::checkbox-group name="interests" hint="Choose one or more"><x-italia::checkbox name="interests[]" value="sport" /></x-italia::checkbox-group>');

        preg_match('/<fieldset[^>]*aria-describedby="([^"]+)"/', $html, $describedBy);
        preg_match('/<small class="form-text" id="([^"]+-hint)"/', $html, $hintId);
        preg_match('/<div class="invalid-feedback d-block" id="([^"]+-error)"/', $html, $errorId);

        $this->assertNotEmpty($describedBy);
        $this->assertNotEmpty($hintId);
        $this->assertNotEmpty($errorId);
        $this->assertSame($hintId[1] . ' ' . $errorId[1], $describedBy[1]);
    }

    public function test_child_checkboxes_do_not_render_group_error(): void
    {
        $this->withViewErrors(['interests' => 'Seleziona almeno un interesse.']);

        $html = (string) $this->blade('<x-italia::checkbox-group name="interests"><x-italia::checkbox name="interests[]" value="sport" /><x-italia::checkbox name="interests[]" value="music" /></x-italia::checkbox-group>');

        $this->assertStringNotContainsString('class="invalid-feedback"', $html);
        $this->assertStringContainsString('class="invalid-feedback d-block"', $html);
    }

    public function test_child_checkboxes_render_inside_fieldset(): void
    {
        $html = (string) $this->blade('<x-italia::checkbox-group name="interests"><x-italia::checkbox name="interests[]" value="sport" label="Sport" /></x-italia::checkbox-group>');

        $this->assertMatchesRegularExpression('/<fieldset[^>]*>.*type="checkbox".*<\/fieldset>/s', $html);
    }

    public function test_legend_defaults_to_headline_of_name(): void
    {
        $html = (string) $this->blade('<x-italia::checkbox-group name="travel_interests" />');

        $this->assertMatchesRegularExpression('/<legend>\s*Travel Interests\s*<\/legend>/s', $html);
    }

    public function test_empty_hint_is_not_rendered(): void
    {
        $html = (string) $this->blade('<x-italia::checkbox-group name="interests" hint="" />');

        $this->assertStringNotContainsString('form-text', $html);
    }

    public function test_error_bag_selects_group_validation_message(): void
    {
        $this->withViewErrors(['interests' => 'Custom bag error.'], 'custom');

        $html = (string) $this->blade('<x-italia::checkbox-group name="interests" error-bag="custom" />');

        $this->assertStringContainsString('Custom bag error.', $html);
    }
}
