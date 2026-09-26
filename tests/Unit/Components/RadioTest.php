<?php

namespace IgorSmoleac\DesignLaravelKit\Tests\Unit\Components;

use IgorSmoleac\DesignLaravelKit\DesignLaravelKitServiceProvider;
use Orchestra\Testbench\TestCase;

class RadioTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [DesignLaravelKitServiceProvider::class];
    }

    public function test_renders_basic_radio(): void
    {
        $html = (string) $this->blade('<x-italia::radio name="gender" value="m" />');

        $this->assertStringContainsString('form-check', $html);
        $this->assertStringContainsString('form-check-input', $html);
        $this->assertStringContainsString('form-check-label', $html);
        $this->assertStringContainsString('type="radio"', $html);
    }

    public function test_checked_attribute_renders(): void
    {
        $html = (string) $this->blade('<x-italia::radio name="gender" value="m" checked />');

        $this->assertStringContainsString('checked="checked"', $html);
    }

    public function test_disabled_attribute_renders(): void
    {
        $html = (string) $this->blade('<x-italia::radio name="gender" value="m" disabled />');

        $this->assertStringContainsString('disabled="disabled"', $html);
    }

    public function test_required_renders_aria_required(): void
    {
        $html = (string) $this->blade('<x-italia::radio name="gender" value="m" required />');

        $this->assertStringContainsString('aria-required="true"', $html);
        $this->assertStringContainsString('required="required"', $html);
    }

    public function test_label_renders_with_for_binding(): void
    {
        $html = (string) $this->blade('<x-italia::radio name="gender" value="m" label="Maschio" />');

        $this->assertStringContainsString('Maschio', $html);

        preg_match('/id="(dlk-gender-\d+)"/', $html, $id);
        preg_match('/for="(dlk-gender-\d+)"/', $html, $for);

        $this->assertNotEmpty($id);
        $this->assertSame($id[1], $for[1]);
    }

    public function test_label_derived_from_name_when_not_provided(): void
    {
        $html = (string) $this->blade('<x-italia::radio name="gender" value="m" />');

        $this->assertStringContainsString('>Gender</label>', $html);
    }

    public function test_hint_renders_with_id(): void
    {
        $html = (string) $this->blade('<x-italia::radio name="gender" value="m" hint="Scegli una opzione" />');

        $this->assertStringContainsString('form-text', $html);
        $this->assertMatchesRegularExpression('/id="dlk-gender-\d+-hint"/', $html);
        $this->assertStringContainsString('aria-describedby="dlk-gender', $html);
    }

    public function test_value_attribute_renders(): void
    {
        $html = (string) $this->blade('<x-italia::radio name="gender" value="m" />');

        $this->assertStringContainsString('value="m"', $html);
    }

    public function test_ids_are_unique_per_instance(): void
    {
        $html = (string) $this->blade(
            '<x-italia::radio name="gender" value="m" /><x-italia::radio name="gender" value="f" />'
        );

        preg_match_all('/id="(dlk-gender-\d+)"/', $html, $ids);

        $this->assertCount(2, $ids[1]);
        $this->assertNotSame($ids[1][0], $ids[1][1]);
    }

    public function test_group_shares_same_name(): void
    {
        $html = (string) $this->blade(
            '<x-italia::radio name="gender" value="m" /><x-italia::radio name="gender" value="f" />'
        );

        $this->assertSame(2, substr_count($html, 'name="gender"'));
    }

    public function test_error_state_renders(): void
    {
        $this->withViewErrors(['gender' => 'Seleziona una opzione.']);

        $html = (string) $this->blade('<x-italia::radio name="gender" value="m" />');

        $this->assertStringContainsString('form-check-input is-invalid', $html);
        $this->assertStringContainsString('aria-invalid="true"', $html);
        $this->assertStringContainsString('role="alert"', $html);
        $this->assertStringContainsString('Seleziona una opzione.', $html);
    }

    public function test_error_id_in_aria_describedby(): void
    {
        $this->withViewErrors(['gender' => 'Required']);

        $html = (string) $this->blade('<x-italia::radio name="gender" value="m" />');

        preg_match('/aria-describedby="([^"]+)"/', $html, $describedBy);
        preg_match('/id="(dlk-gender-\d+-error)"/', $html, $errorId);

        $this->assertNotEmpty($describedBy);
        $this->assertSame($errorId[1], $describedBy[1]);
    }

    public function test_old_input_checks_matching_radio(): void
    {
        $this->withSession(['_old_input' => ['gender' => 'f']]);

        $female = (string) $this->blade('<x-italia::radio name="gender" value="f" />');
        $male = (string) $this->blade('<x-italia::radio name="gender" value="m" />');

        $this->assertStringContainsString('checked="checked"', $female);
        $this->assertStringNotContainsString('checked=', $male);
    }

    public function test_custom_attributes_passed_through(): void
    {
        $html = (string) $this->blade('<x-italia::radio name="gender" value="m" class="custom" data-test="foo" />');

        $this->assertStringContainsString('form-check-input custom', $html);
        $this->assertStringContainsString('data-test="foo"', $html);
    }

    public function test_explicit_id_is_respected(): void
    {
        $html = (string) $this->blade('<x-italia::radio name="gender" value="m" id="my-gender" />');

        $this->assertStringContainsString('id="my-gender"', $html);
        $this->assertStringContainsString('for="my-gender"', $html);
    }

    public function test_named_error_bag_is_respected(): void
    {
        $this->withViewErrors(['gender' => 'Bag error.'], 'custom');

        $custom = (string) $this->blade('<x-italia::radio name="gender" value="m" bag="custom" />');

        $this->assertStringContainsString('is-invalid', $custom);
        $this->assertStringContainsString('Bag error.', $custom);

        $default = (string) $this->blade('<x-italia::radio name="gender" value="m" />');

        $this->assertStringNotContainsString('is-invalid', $default);
    }
}
