<?php

namespace IgorSmoleac\DesignLaravelKit\Tests\Unit\Components;

use IgorSmoleac\DesignLaravelKit\DesignLaravelKitServiceProvider;
use Orchestra\Testbench\TestCase;

class CheckboxTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [DesignLaravelKitServiceProvider::class];
    }

    public function test_renders_basic_checkbox(): void
    {
        $html = (string) $this->blade('<x-italia::checkbox name="terms" />');

        $this->assertStringContainsString('form-check', $html);
        $this->assertStringContainsString('form-check-input', $html);
        $this->assertStringContainsString('form-check-label', $html);
        $this->assertStringContainsString('type="checkbox"', $html);
    }

    public function test_checked_attribute_renders(): void
    {
        $html = (string) $this->blade('<x-italia::checkbox name="terms" checked />');

        $this->assertStringContainsString('checked="checked"', $html);
    }

    public function test_no_checked_attribute_by_default(): void
    {
        $html = (string) $this->blade('<x-italia::checkbox name="terms" />');

        $this->assertStringNotContainsString('checked=', $html);
    }

    public function test_disabled_attribute_renders(): void
    {
        $html = (string) $this->blade('<x-italia::checkbox name="terms" disabled />');

        $this->assertStringContainsString('disabled="disabled"', $html);
    }

    public function test_required_renders_aria_required(): void
    {
        $html = (string) $this->blade('<x-italia::checkbox name="terms" required />');

        $this->assertStringContainsString('aria-required="true"', $html);
        $this->assertStringContainsString('required="required"', $html);
    }

    public function test_label_renders_with_for_binding(): void
    {
        $html = (string) $this->blade('<x-italia::checkbox name="terms" label="Accetto i termini" />');

        $this->assertStringContainsString('Accetto i termini', $html);

        preg_match('/id="(dlk-terms-\d+)"/', $html, $id);
        preg_match('/for="(dlk-terms-\d+)"/', $html, $for);

        $this->assertNotEmpty($id);
        $this->assertSame($id[1], $for[1]);
    }

    public function test_label_derived_from_name_when_not_provided(): void
    {
        $html = (string) $this->blade('<x-italia::checkbox name="terms" />');

        $this->assertStringContainsString('>Terms</label>', $html);
    }

    public function test_hint_renders_with_id(): void
    {
        $html = (string) $this->blade('<x-italia::checkbox name="terms" hint="Obbligatorio" />');

        $this->assertStringContainsString('form-text', $html);
        $this->assertMatchesRegularExpression('/id="dlk-terms-\d+-hint"/', $html);
        $this->assertStringContainsString('aria-describedby="dlk-terms', $html);
    }

    public function test_value_attribute_renders(): void
    {
        $html = (string) $this->blade('<x-italia::checkbox name="newsletter" value="yes" />');

        $this->assertStringContainsString('value="yes"', $html);
    }

    public function test_no_value_attribute_when_not_provided(): void
    {
        $html = (string) $this->blade('<x-italia::checkbox name="terms" />');

        $this->assertStringNotContainsString('value=', $html);
    }

    public function test_ids_are_unique_per_instance(): void
    {
        $html = (string) $this->blade('<x-italia::checkbox name="terms" /><x-italia::checkbox name="terms" />');

        preg_match_all('/id="(dlk-terms-\d+)"/', $html, $ids);

        $this->assertCount(2, $ids[1]);
        $this->assertNotSame($ids[1][0], $ids[1][1]);
    }

    public function test_name_with_brackets_renders_verbatim(): void
    {
        $html = (string) $this->blade('<x-italia::checkbox name="interests[]" value="sport" />');

        $this->assertStringContainsString('name="interests[]"', $html);
    }

    public function test_is_invalid_class_when_error_exists(): void
    {
        $this->withViewErrors(['terms' => 'Devi accettare i termini.']);

        $html = (string) $this->blade('<x-italia::checkbox name="terms" />');

        $this->assertStringContainsString('form-check-input is-invalid', $html);
    }

    public function test_aria_invalid_true_when_error_exists(): void
    {
        $this->withViewErrors(['terms' => 'Required']);

        $html = (string) $this->blade('<x-italia::checkbox name="terms" />');

        $this->assertStringContainsString('aria-invalid="true"', $html);
    }

    public function test_error_message_rendered_with_role_alert(): void
    {
        $this->withViewErrors(['terms' => 'Devi accettare i termini.']);

        $html = (string) $this->blade('<x-italia::checkbox name="terms" />');

        $this->assertStringContainsString('invalid-feedback', $html);
        $this->assertStringContainsString('role="alert"', $html);
        $this->assertStringContainsString('Devi accettare i termini.', $html);
    }

    public function test_error_id_in_aria_describedby(): void
    {
        $this->withViewErrors(['terms' => 'Required']);

        $html = (string) $this->blade('<x-italia::checkbox name="terms" />');

        preg_match('/aria-describedby="([^"]+)"/', $html, $describedBy);
        preg_match('/id="(dlk-terms-\d+-error)"/', $html, $errorId);

        $this->assertNotEmpty($describedBy);
        $this->assertSame($errorId[1], $describedBy[1]);
    }

    public function test_dot_notation_error_key_for_array_name(): void
    {
        $this->withViewErrors(['interests' => 'Seleziona almeno un interesse.']);

        $html = (string) $this->blade('<x-italia::checkbox name="interests[]" value="sport" />');

        $this->assertStringContainsString('is-invalid', $html);
        $this->assertStringContainsString('Seleziona almeno un interesse.', $html);
    }

    public function test_hint_hidden_when_error_exists(): void
    {
        $this->withViewErrors(['terms' => 'Required']);

        $html = (string) $this->blade('<x-italia::checkbox name="terms" hint="Hint text" />');

        $this->assertStringContainsString('invalid-feedback', $html);
        $this->assertStringNotContainsString('form-text', $html);
        $this->assertStringNotContainsString('Hint text', $html);
    }

    public function test_old_input_checks_checkbox(): void
    {
        $this->withSession(['_old_input' => ['terms' => '1']]);

        $html = (string) $this->blade('<x-italia::checkbox name="terms" value="1" />');

        $this->assertStringContainsString('checked="checked"', $html);
    }

    public function test_old_input_array_checks_matching_value(): void
    {
        $this->withSession(['_old_input' => ['interests' => ['sport', 'music']]]);

        $sport = (string) $this->blade('<x-italia::checkbox name="interests[]" value="sport" />');
        $travel = (string) $this->blade('<x-italia::checkbox name="interests[]" value="travel" />');

        $this->assertStringContainsString('checked="checked"', $sport);
        $this->assertStringNotContainsString('checked=', $travel);
    }

    public function test_old_input_unchecks_when_not_submitted(): void
    {
        $this->withSession(['_old_input' => ['interests' => []]]);

        $html = (string) $this->blade('<x-italia::checkbox name="interests[]" value="sport" checked />');

        $this->assertStringNotContainsString('checked=', $html);
    }

    public function test_custom_attributes_passed_through(): void
    {
        $html = (string) $this->blade('<x-italia::checkbox name="terms" class="custom" data-test="foo" />');

        $this->assertStringContainsString('form-check-input custom', $html);
        $this->assertStringContainsString('data-test="foo"', $html);
    }

    public function test_explicit_id_is_respected(): void
    {
        $html = (string) $this->blade('<x-italia::checkbox name="terms" id="my-terms" />');

        $this->assertStringContainsString('id="my-terms"', $html);
        $this->assertStringContainsString('for="my-terms"', $html);
    }

    public function test_named_error_bag_is_respected(): void
    {
        $this->withViewErrors(['terms' => 'Bag error.'], 'custom');

        $custom = (string) $this->blade('<x-italia::checkbox name="terms" bag="custom" />');

        $this->assertStringContainsString('is-invalid', $custom);
        $this->assertStringContainsString('Bag error.', $custom);

        $default = (string) $this->blade('<x-italia::checkbox name="terms" />');

        $this->assertStringNotContainsString('is-invalid', $default);
    }
}
