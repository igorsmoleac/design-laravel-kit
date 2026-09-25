<?php

namespace IgorSmoleac\DesignLaravelKit\Tests\Unit\Components;

use IgorSmoleac\DesignLaravelKit\DesignLaravelKitServiceProvider;
use Orchestra\Testbench\TestCase;

class SelectTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [DesignLaravelKitServiceProvider::class];
    }

    public function test_renders_select_element(): void
    {
        $html = (string) $this->blade('<x-italia::select name="country" />');

        $this->assertStringContainsString('<select', $html);
        $this->assertStringContainsString('form-select', $html);
        $this->assertStringNotContainsString('<input', $html);
    }

    public function test_renders_options(): void
    {
        $html = (string) $this->blade('<x-italia::select name="country" :options="[\'it\' => \'Italia\', \'fr\' => \'Francia\']" />');

        $this->assertStringContainsString('<option value="it">Italia</option>', $html);
        $this->assertStringContainsString('<option value="fr">Francia</option>', $html);
    }

    public function test_renders_optgroup_for_nested_options(): void
    {
        $html = (string) $this->blade('<x-italia::select name="city" :options="[\'Nord\' => [\'mi\' => \'Milano\'], \'Sud\' => [\'na\' => \'Napoli\']]" />');

        $this->assertStringContainsString('<optgroup label="Nord">', $html);
        $this->assertStringContainsString('<optgroup label="Sud">', $html);
        $this->assertStringContainsString('<option value="mi">Milano</option>', $html);
        $this->assertStringContainsString('<option value="na">Napoli</option>', $html);
    }

    public function test_placeholder_renders_as_first_disabled_option(): void
    {
        $html = (string) $this->blade('<x-italia::select name="country" :options="[\'it\' => \'Italia\']" placeholder="Seleziona..." />');

        $this->assertMatchesRegularExpression('/<option value="" disabled selected>Seleziona\.\.\.<\/option>/', $html);
    }

    public function test_placeholder_not_selected_when_value_selected(): void
    {
        $html = (string) $this->blade('<x-italia::select name="country" :options="[\'it\' => \'Italia\']" placeholder="Seleziona..." selected="it" />');

        $this->assertStringContainsString('<option value="" disabled>', $html);
        $this->assertStringNotContainsString('disabled selected', $html);
    }

    public function test_selected_value_renders(): void
    {
        $html = (string) $this->blade('<x-italia::select name="country" :options="[\'it\' => \'Italia\', \'fr\' => \'Francia\']" selected="fr" />');

        $this->assertStringContainsString('<option value="fr" selected>Francia</option>', $html);
        $this->assertStringNotContainsString('<option value="it" selected', $html);
    }

    public function test_selected_from_old_input_when_no_selected_prop(): void
    {
        $this->withSession(['_old_input' => ['country' => 'fr']]);

        $html = (string) $this->blade('<x-italia::select name="country" :options="[\'it\' => \'Italia\', \'fr\' => \'Francia\']" />');

        $this->assertStringContainsString('<option value="fr" selected>Francia</option>', $html);
    }

    public function test_selected_prop_wins_over_old_input(): void
    {
        $this->withSession(['_old_input' => ['country' => 'fr']]);

        $html = (string) $this->blade('<x-italia::select name="country" :options="[\'it\' => \'Italia\', \'fr\' => \'Francia\']" selected="it" />');

        $this->assertStringContainsString('<option value="it" selected>Italia</option>', $html);
    }

    public function test_multiple_appends_brackets_to_name(): void
    {
        $html = (string) $this->blade('<x-italia::select name="roles" multiple />');

        $this->assertStringContainsString('name="roles[]"', $html);
    }

    public function test_multiple_keeps_existing_brackets(): void
    {
        $html = (string) $this->blade('<x-italia::select name="roles[]" multiple />');

        $this->assertStringContainsString('name="roles[]"', $html);
    }

    public function test_single_select_has_no_brackets(): void
    {
        $html = (string) $this->blade('<x-italia::select name="country" />');

        $this->assertStringContainsString('name="country"', $html);
        $this->assertStringNotContainsString('name="country[]"', $html);
    }

    public function test_multiple_renders_multiple_attribute(): void
    {
        $html = (string) $this->blade('<x-italia::select name="roles" multiple />');

        $this->assertStringContainsString('multiple="multiple"', $html);
    }

    public function test_multiple_with_array_selected_marks_all(): void
    {
        $html = (string) $this->blade('<x-italia::select name="roles" multiple :options="[\'admin\' => \'Admin\', \'editor\' => \'Editor\', \'viewer\' => \'Viewer\']" :selected="[\'admin\', \'viewer\']" />');

        $this->assertStringContainsString('<option value="admin" selected>Admin</option>', $html);
        $this->assertStringContainsString('<option value="viewer" selected>Viewer</option>', $html);
        $this->assertStringNotContainsString('<option value="editor" selected', $html);
    }

    public function test_multiple_selected_from_old_input_array(): void
    {
        $this->withSession(['_old_input' => ['roles' => ['editor']]]);

        $html = (string) $this->blade('<x-italia::select name="roles" multiple :options="[\'admin\' => \'Admin\', \'editor\' => \'Editor\']" />');

        $this->assertStringContainsString('<option value="editor" selected>Editor</option>', $html);
        $this->assertStringNotContainsString('<option value="admin" selected', $html);
    }

    public function test_no_error_state_when_no_errors(): void
    {
        $html = (string) $this->blade('<x-italia::select name="country" />');

        $this->assertStringNotContainsString('is-invalid', $html);
        $this->assertStringNotContainsString('aria-invalid', $html);
        $this->assertStringNotContainsString('invalid-feedback', $html);
    }

    public function test_is_invalid_class_when_error_exists(): void
    {
        $this->withViewErrors(['country' => 'The country field is required.']);

        $html = (string) $this->blade('<x-italia::select name="country" />');

        $this->assertStringContainsString('form-select is-invalid', $html);
    }

    public function test_aria_invalid_true_when_error_exists(): void
    {
        $this->withViewErrors(['country' => 'Required']);

        $html = (string) $this->blade('<x-italia::select name="country" />');

        $this->assertStringContainsString('aria-invalid="true"', $html);
    }

    public function test_error_message_rendered_with_role_alert(): void
    {
        $this->withViewErrors(['country' => 'The country field is required.']);

        $html = (string) $this->blade('<x-italia::select name="country" />');

        $this->assertStringContainsString('role="alert"', $html);
        $this->assertStringContainsString('aria-live="polite"', $html);
        $this->assertStringContainsString('The country field is required.', $html);
    }

    public function test_dot_notation_error_key(): void
    {
        $this->withViewErrors(['user.country' => 'The country is invalid.']);

        $html = (string) $this->blade('<x-italia::select name="user[country]" />');

        $this->assertStringContainsString('is-invalid', $html);
        $this->assertStringContainsString('The country is invalid.', $html);
    }

    public function test_hint_renders_with_id(): void
    {
        $html = (string) $this->blade('<x-italia::select name="country" hint="Scegli un paese" />');

        $this->assertStringContainsString('form-text', $html);
        $this->assertMatchesRegularExpression('/id="dlk-country-\d+-hint"/', $html);
        $this->assertStringContainsString('aria-describedby="dlk-country', $html);
    }

    public function test_renders_required_attribute(): void
    {
        $html = (string) $this->blade('<x-italia::select name="country" required />');

        $this->assertStringContainsString('aria-required="true"', $html);
        $this->assertStringContainsString('required="required"', $html);
    }

    public function test_renders_disabled_attribute(): void
    {
        $html = (string) $this->blade('<x-italia::select name="country" disabled />');

        $this->assertStringContainsString('disabled="disabled"', $html);
    }

    public function test_id_is_derived_from_name(): void
    {
        $html = (string) $this->blade('<x-italia::select name="country" />');

        $this->assertMatchesRegularExpression('/id="dlk-country-\d+"/', $html);
    }

    public function test_label_for_matches_select_id(): void
    {
        $html = (string) $this->blade('<x-italia::select name="country" />');

        preg_match('/id="(dlk-country-\d+)"/', $html, $id);
        preg_match('/for="(dlk-country-\d+)"/', $html, $for);

        $this->assertNotEmpty($id);
        $this->assertSame($id[1], $for[1]);
    }

    public function test_renders_provided_label(): void
    {
        $html = (string) $this->blade('<x-italia::select name="country" label="Paese" />');

        $this->assertStringContainsString('Paese', $html);
    }

    public function test_custom_class_passed_through(): void
    {
        $html = (string) $this->blade('<x-italia::select name="country" class="custom" />');

        $this->assertStringContainsString('form-select custom', $html);
    }

    public function test_wire_model_passed_through(): void
    {
        $html = (string) $this->blade('<x-italia::select name="country" wire:model="country" />');

        $this->assertStringContainsString('wire:model="country"', $html);
    }

    public function test_floating_disabled_renders_label_before_select(): void
    {
        $html = (string) $this->blade('<x-italia::select name="country" :floating="false" />');

        $this->assertStringNotContainsString('dlk-floating', $html);
        $this->assertStringContainsString('form-label', $html);
        $this->assertMatchesRegularExpression('/<label[^>]*>.*<\/label>\s*<select/', $html);
    }

    public function test_named_error_bag_is_respected(): void
    {
        $this->withViewErrors(['country' => 'Bag error.'], 'custom');

        $custom = (string) $this->blade('<x-italia::select name="country" bag="custom" />');

        $this->assertStringContainsString('is-invalid', $custom);
        $this->assertStringContainsString('Bag error.', $custom);

        $default = (string) $this->blade('<x-italia::select name="country" />');

        $this->assertStringNotContainsString('is-invalid', $default);
    }
}
