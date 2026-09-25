<?php

namespace IgorSmoleac\DesignLaravelKit\Tests\Unit\Components;

use IgorSmoleac\DesignLaravelKit\DesignLaravelKitServiceProvider;
use Orchestra\Testbench\TestCase;

class TextareaTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [DesignLaravelKitServiceProvider::class];
    }

    public function test_renders_textarea_element(): void
    {
        $html = (string) $this->blade('<x-italia::textarea name="bio" />');

        $this->assertStringContainsString('<textarea', $html);
        $this->assertStringContainsString('form-control', $html);
        $this->assertStringNotContainsString('<input', $html);
    }

    public function test_renders_default_rows(): void
    {
        $html = (string) $this->blade('<x-italia::textarea name="bio" />');

        $this->assertStringContainsString('rows="3"', $html);
    }

    public function test_renders_custom_rows(): void
    {
        $html = (string) $this->blade('<x-italia::textarea name="bio" :rows="5" />');

        $this->assertStringContainsString('rows="5"', $html);
    }

    public function test_renders_label_from_name_when_not_provided(): void
    {
        $html = (string) $this->blade('<x-italia::textarea name="message" />');

        $this->assertStringContainsString('>Message</label>', $html);
    }

    public function test_renders_provided_label(): void
    {
        $html = (string) $this->blade('<x-italia::textarea name="bio" label="Biografia" />');

        $this->assertStringContainsString('Biografia', $html);
    }

    public function test_renders_required_attribute(): void
    {
        $html = (string) $this->blade('<x-italia::textarea name="bio" required />');

        $this->assertStringContainsString('aria-required="true"', $html);
        $this->assertStringContainsString('required="required"', $html);
    }

    public function test_renders_disabled_attribute(): void
    {
        $html = (string) $this->blade('<x-italia::textarea name="bio" disabled />');

        $this->assertStringContainsString('disabled="disabled"', $html);
    }

    public function test_renders_readonly_attribute(): void
    {
        $html = (string) $this->blade('<x-italia::textarea name="bio" readonly />');

        $this->assertStringContainsString('readonly="readonly"', $html);
    }

    public function test_id_is_derived_from_name(): void
    {
        $html = (string) $this->blade('<x-italia::textarea name="bio" />');

        $this->assertMatchesRegularExpression('/id="dlk-bio-\d+"/', $html);
    }

    public function test_label_for_matches_textarea_id(): void
    {
        $html = (string) $this->blade('<x-italia::textarea name="bio" />');

        preg_match('/id="(dlk-bio-\d+)"/', $html, $id);
        preg_match('/for="(dlk-bio-\d+)"/', $html, $for);

        $this->assertNotEmpty($id);
        $this->assertSame($id[1], $for[1]);
    }

    public function test_no_error_state_when_no_errors(): void
    {
        $html = (string) $this->blade('<x-italia::textarea name="bio" />');

        $this->assertStringNotContainsString('is-invalid', $html);
        $this->assertStringNotContainsString('aria-invalid', $html);
        $this->assertStringNotContainsString('invalid-feedback', $html);
    }

    public function test_is_invalid_class_when_error_exists(): void
    {
        $this->withViewErrors(['bio' => 'The bio field is required.']);

        $html = (string) $this->blade('<x-italia::textarea name="bio" />');

        $this->assertStringContainsString('form-control is-invalid', $html);
    }

    public function test_aria_invalid_true_when_error_exists(): void
    {
        $this->withViewErrors(['bio' => 'Required']);

        $html = (string) $this->blade('<x-italia::textarea name="bio" />');

        $this->assertStringContainsString('aria-invalid="true"', $html);
    }

    public function test_error_message_rendered_with_role_alert(): void
    {
        $this->withViewErrors(['bio' => 'The bio field is required.']);

        $html = (string) $this->blade('<x-italia::textarea name="bio" />');

        $this->assertStringContainsString('role="alert"', $html);
        $this->assertStringContainsString('aria-live="polite"', $html);
        $this->assertStringContainsString('The bio field is required.', $html);
    }

    public function test_error_id_in_aria_describedby(): void
    {
        $this->withViewErrors(['bio' => 'Required']);

        $html = (string) $this->blade('<x-italia::textarea name="bio" />');

        preg_match('/aria-describedby="([^"]+)"/', $html, $describedBy);
        preg_match('/id="(dlk-bio-\d+-error)"/', $html, $errorId);

        $this->assertNotEmpty($describedBy);
        $this->assertSame($errorId[1], $describedBy[1]);
    }

    public function test_dot_notation_error_key(): void
    {
        $this->withViewErrors(['user.bio' => 'The bio is too long.']);

        $html = (string) $this->blade('<x-italia::textarea name="user[bio]" />');

        $this->assertStringContainsString('is-invalid', $html);
        $this->assertStringContainsString('The bio is too long.', $html);
    }

    public function test_hint_renders_with_id(): void
    {
        $html = (string) $this->blade('<x-italia::textarea name="bio" hint="Max 500 caratteri" />');

        $this->assertStringContainsString('form-text', $html);
        $this->assertMatchesRegularExpression('/id="dlk-bio-\d+-hint"/', $html);
        $this->assertStringContainsString('aria-describedby="dlk-bio', $html);
    }

    public function test_hint_hidden_when_error_exists(): void
    {
        $this->withViewErrors(['bio' => 'Required']);

        $html = (string) $this->blade('<x-italia::textarea name="bio" hint="Max 500 caratteri" />');

        $this->assertStringContainsString('invalid-feedback', $html);
        $this->assertStringNotContainsString('form-text', $html);
        $this->assertStringNotContainsString('Max 500 caratteri', $html);
    }

    public function test_value_renders_between_tags_not_as_attribute(): void
    {
        $html = (string) $this->blade('<x-italia::textarea name="bio" value="Vecchio testo" />');

        $this->assertStringContainsString('>Vecchio testo</textarea>', $html);
        $this->assertStringNotContainsString('value="Vecchio testo"', $html);
    }

    public function test_old_value_is_used_when_no_value_prop(): void
    {
        $this->withSession(['_old_input' => ['bio' => 'Vecchia bio']]);

        $html = (string) $this->blade('<x-italia::textarea name="bio" />');

        $this->assertStringContainsString('>Vecchia bio</textarea>', $html);
    }

    public function test_label_has_active_class_when_value_provided(): void
    {
        $html = (string) $this->blade('<x-italia::textarea name="bio" value="Testo" />');

        $this->assertStringContainsString('class="active"', $html);
    }

    public function test_label_has_active_class_when_old_value_present(): void
    {
        session()->flashInput(['bio' => 'Testo']);

        $html = (string) $this->blade('<x-italia::textarea name="bio" />');

        $this->assertStringContainsString('class="active"', $html);
    }

    public function test_label_has_no_active_class_when_empty(): void
    {
        $html = (string) $this->blade('<x-italia::textarea name="bio" />');

        $this->assertStringNotContainsString('class="active"', $html);
    }

    public function test_custom_class_passed_through(): void
    {
        $html = (string) $this->blade('<x-italia::textarea name="bio" class="custom" />');

        $this->assertStringContainsString('form-control custom', $html);
    }

    public function test_wire_model_passed_through(): void
    {
        $html = (string) $this->blade('<x-italia::textarea name="bio" wire:model="bio" />');

        $this->assertStringContainsString('wire:model="bio"', $html);
    }

    public function test_floating_disabled_renders_without_dlk_floating(): void
    {
        $html = (string) $this->blade('<x-italia::textarea name="bio" :floating="false" />');

        $this->assertStringNotContainsString('dlk-floating', $html);
        $this->assertStringContainsString('form-label', $html);
    }

    public function test_named_error_bag_is_respected(): void
    {
        $this->withViewErrors(['bio' => 'Bag error.'], 'custom');

        $custom = (string) $this->blade('<x-italia::textarea name="bio" bag="custom" />');

        $this->assertStringContainsString('is-invalid', $custom);
        $this->assertStringContainsString('Bag error.', $custom);

        $default = (string) $this->blade('<x-italia::textarea name="bio" />');

        $this->assertStringNotContainsString('is-invalid', $default);
    }
}
