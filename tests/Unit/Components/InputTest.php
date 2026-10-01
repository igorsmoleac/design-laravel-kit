<?php

namespace IgorSmoleac\DesignLaravelKit\Tests\Unit\Components;

use IgorSmoleac\DesignLaravelKit\DesignLaravelKitServiceProvider;
use Orchestra\Testbench\TestCase;

class InputTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [DesignLaravelKitServiceProvider::class];
    }

    public function test_renders_text_input_by_default(): void
    {
        $html = (string) $this->blade('<x-italia::input name="email" />');

        $this->assertStringContainsString('type="text"', $html);
        $this->assertStringContainsString('form-control', $html);
    }

    public function test_renders_with_specified_type(): void
    {
        $html = (string) $this->blade('<x-italia::input name="email" type="email" />');

        $this->assertStringContainsString('type="email"', $html);
    }

    public function test_renders_label_from_name_when_not_provided(): void
    {
        $html = (string) $this->blade('<x-italia::input name="email" />');

        $this->assertStringContainsString('>Email</label>', $html);
    }

    public function test_renders_provided_label(): void
    {
        $html = (string) $this->blade('<x-italia::input name="email" label="Indirizzo email" />');

        $this->assertStringContainsString('Indirizzo email', $html);
    }

    public function test_renders_required_attribute(): void
    {
        $html = (string) $this->blade('<x-italia::input name="email" required />');

        $this->assertStringContainsString('aria-required="true"', $html);
        $this->assertStringContainsString('required="required"', $html);
    }

    public function test_required_field_shows_visual_marker(): void
    {
        $floatingHtml = (string) $this->blade('<x-italia::input name="email" required />');
        $staticHtml = (string) $this->blade('<x-italia::input name="email" required :floating="false" />');

        foreach ([$floatingHtml, $staticHtml] as $html) {
            $this->assertStringContainsString('<span class="text-danger" aria-hidden="true">*</span>', $html);
        }
    }

    public function test_required_field_has_visually_hidden_label(): void
    {
        $floatingHtml = (string) $this->blade('<x-italia::input name="email" required />');
        $staticHtml = (string) $this->blade('<x-italia::input name="email" required :floating="false" />');

        foreach ([$floatingHtml, $staticHtml] as $html) {
            $this->assertStringContainsString('<span class="visually-hidden">(obbligatorio)</span>', $html);
        }
    }

    public function test_optional_field_has_no_marker(): void
    {
        $floatingHtml = (string) $this->blade('<x-italia::input name="email" />');
        $staticHtml = (string) $this->blade('<x-italia::input name="email" :floating="false" />');

        foreach ([$floatingHtml, $staticHtml] as $html) {
            $this->assertStringNotContainsString('text-danger', $html);
            $this->assertStringNotContainsString('(obbligatorio)', $html);
        }
    }

    public function test_renders_disabled_attribute(): void
    {
        $html = (string) $this->blade('<x-italia::input name="email" disabled />');

        $this->assertStringContainsString('disabled="disabled"', $html);
    }

    public function test_renders_readonly_attribute(): void
    {
        $html = (string) $this->blade('<x-italia::input name="email" readonly />');

        $this->assertStringContainsString('readonly="readonly"', $html);
    }

    public function test_id_is_derived_from_name(): void
    {
        $html = (string) $this->blade('<x-italia::input name="email" />');

        $this->assertMatchesRegularExpression('/id="dlk-email-\d+"/', $html);
    }

    public function test_label_for_matches_input_id(): void
    {
        $html = (string) $this->blade('<x-italia::input name="email" />');

        preg_match('/id="(dlk-email-\d+)"/', $html, $id);
        preg_match('/for="(dlk-email-\d+)"/', $html, $for);

        $this->assertNotEmpty($id);
        $this->assertSame($id[1], $for[1]);
    }

    public function test_hint_renders_with_id(): void
    {
        $html = (string) $this->blade('<x-italia::input name="email" hint="name@example.com" />');

        $this->assertStringContainsString('form-text', $html);
        $this->assertMatchesRegularExpression('/id="dlk-email-\d+-hint"/', $html);
        $this->assertStringContainsString('aria-describedby="dlk-email', $html);
    }

    public function test_no_error_state_when_no_errors(): void
    {
        $html = (string) $this->blade('<x-italia::input name="email" />');

        $this->assertStringNotContainsString('is-invalid', $html);
        $this->assertStringNotContainsString('aria-invalid', $html);
        $this->assertStringNotContainsString('invalid-feedback', $html);
    }

    public function test_is_invalid_class_when_error_exists(): void
    {
        $this->withViewErrors(['email' => 'The email field is required.']);

        $html = (string) $this->blade('<x-italia::input name="email" />');

        $this->assertStringContainsString('form-control is-invalid', $html);
    }

    public function test_aria_invalid_true_when_error_exists(): void
    {
        $this->withViewErrors(['email' => 'Required']);

        $html = (string) $this->blade('<x-italia::input name="email" />');

        $this->assertStringContainsString('aria-invalid="true"', $html);
    }

    public function test_error_message_rendered_with_role_alert(): void
    {
        $this->withViewErrors(['email' => 'The email field is required.']);

        $html = (string) $this->blade('<x-italia::input name="email" />');

        $this->assertStringContainsString('role="alert"', $html);
        $this->assertStringContainsString('aria-live="polite"', $html);
        $this->assertStringContainsString('The email field is required.', $html);
    }

    public function test_error_id_in_aria_describedby(): void
    {
        $this->withViewErrors(['email' => 'Required']);

        $html = (string) $this->blade('<x-italia::input name="email" />');

        preg_match('/aria-describedby="([^"]+)"/', $html, $describedBy);
        preg_match('/id="(dlk-email-\d+-error)"/', $html, $errorId);

        $this->assertNotEmpty($describedBy);
        $this->assertSame($errorId[1], $describedBy[1]);
    }

    public function test_dot_notation_error_key(): void
    {
        $this->withViewErrors(['user.email' => 'The email is invalid.']);

        $html = (string) $this->blade('<x-italia::input name="user[email]" />');

        $this->assertStringContainsString('is-invalid', $html);
        $this->assertStringContainsString('The email is invalid.', $html);
    }

    public function test_hint_and_error_are_both_rendered_when_error_exists(): void
    {
        $this->withViewErrors(['email' => 'Required']);

        $html = (string) $this->blade('<x-italia::input name="email" hint="Hint text" />');

        $this->assertStringContainsString('form-text', $html);
        $this->assertStringContainsString('Hint text', $html);
        $this->assertStringContainsString('invalid-feedback', $html);
        $this->assertStringContainsString('Required', $html);
    }

    public function test_aria_describedby_includes_both_hint_and_error_ids(): void
    {
        $this->withViewErrors(['email' => 'Required']);

        $html = (string) $this->blade('<x-italia::input name="email" hint="Hint text" />');

        preg_match('/aria-describedby="([^"]+)"/', $html, $describedBy);
        preg_match('/id="([^"]+-hint)"/', $html, $hintId);
        preg_match('/id="([^"]+-error)"/', $html, $errorId);

        $this->assertNotEmpty($describedBy);
        $this->assertNotEmpty($hintId);
        $this->assertNotEmpty($errorId);
        $this->assertSame($hintId[1] . ' ' . $errorId[1], $describedBy[1]);
    }

    public function test_value_prop_renders(): void
    {
        $html = (string) $this->blade('<x-italia::input name="email" value="test@example.com" />');

        $this->assertStringContainsString('value="test@example.com"', $html);
    }

    public function test_old_value_is_used_when_no_value_prop(): void
    {
        $this->withSession(['_old_input' => ['email' => 'old@example.com']]);

        $html = (string) $this->blade('<x-italia::input name="email" />');

        $this->assertStringContainsString('value="old@example.com"', $html);
    }

    public function test_input_uses_old_value_over_prop_when_old_exists(): void
    {
        $this->withSession(['_old_input' => ['email' => 'UserInput']]);

        $html = (string) $this->blade('<x-italia::input name="email" value="Default" />');

        $this->assertStringContainsString('value="UserInput"', $html);
        $this->assertStringNotContainsString('value="Default"', $html);
    }

    public function test_input_falls_back_to_value_prop_when_no_old(): void
    {
        $html = (string) $this->blade('<x-italia::input name="email" value="Default" />');

        $this->assertStringContainsString('value="Default"', $html);
    }

    public function test_custom_class_passed_through(): void
    {
        $html = (string) $this->blade('<x-italia::input name="email" class="custom" />');

        $this->assertStringContainsString('form-control custom', $html);
    }

    public function test_wire_model_passed_through(): void
    {
        $html = (string) $this->blade('<x-italia::input name="email" wire:model="email" />');

        $this->assertStringContainsString('wire:model="email"', $html);
    }

    public function test_label_has_active_class_when_value_provided(): void
    {
        $html = (string) $this->blade('<x-italia::input name="email" value="test@example.com" />');

        $this->assertStringContainsString('class="active"', $html);
    }

    public function test_label_has_active_class_when_old_value_present(): void
    {
        session()->flashInput(['email' => 'test@example.com']);

        $html = (string) $this->blade('<x-italia::input name="email" />');

        $this->assertStringContainsString('class="active"', $html);
    }

    public function test_label_has_no_active_class_when_empty(): void
    {
        $html = (string) $this->blade('<x-italia::input name="email" />');

        $this->assertStringNotContainsString('class="active"', $html);
    }

    public function test_named_bag_is_respected(): void
    {
        $this->withViewErrors(['email' => 'Bag error.'], 'custom');

        $custom = (string) $this->blade('<x-italia::input name="email" bag="custom" />');

        $this->assertStringContainsString('is-invalid', $custom);
        $this->assertStringContainsString('Bag error.', $custom);

        $default = (string) $this->blade('<x-italia::input name="email" />');

        $this->assertStringNotContainsString('is-invalid', $default);
    }
}
