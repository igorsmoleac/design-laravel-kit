<?php

namespace IgorSmoleac\DesignLaravelKit\Tests\Unit\Components;

use IgorSmoleac\DesignLaravelKit\Components\BaseComponent;
use Illuminate\Support\Facades\View;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Str;
use Illuminate\Support\ViewErrorBag;
use Illuminate\View\ComponentAttributeBag;
use Orchestra\Testbench\TestCase;

class DummyComponent extends BaseComponent
{
    public function render(): string
    {
        return 'dummy';
    }
}

class DummyHintComponent extends BaseComponent
{
    public function render(): string
    {
        return 'dummy';
    }

    protected function hasHint(): bool
    {
        return true;
    }
}

class DummyCheckboxComponent extends BaseComponent
{
    public function render(): string
    {
        return 'dummy';
    }

    protected function idSeed(): ?string
    {
        $name = $this->attributes?->get('name');

        if ($name === null) {
            return null;
        }

        return trim($this->slugifyId($name) . '-' . Str::slug((string) $this->attributes?->get('value', '')), '-');
    }
}

class BaseComponentTest extends TestCase
{
    private function makeComponent(array $attributes = []): DummyComponent
    {
        $component = new DummyComponent;
        $component->attributes = new ComponentAttributeBag($attributes);

        return $component;
    }

    public function test_id_prefix_defaults_to_dlk(): void
    {
        $this->assertSame('dlk', DummyComponent::idPrefix());
    }

    public function test_id_prefix_is_resolved_from_config(): void
    {
        config(['design-laravel-kit.id_prefix' => 'bs-italia']);

        $this->assertSame('bs-italia', DummyComponent::idPrefix());
    }

    public function test_id_is_generated_from_name_attribute_with_unique_suffix(): void
    {
        $this->assertMatchesRegularExpression('/^dlk-email-\d+$/', $this->makeComponent(['name' => 'email'])->id());
    }

    public function test_id_converts_bracket_syntax_to_dashes(): void
    {
        $this->assertMatchesRegularExpression('/^dlk-user-email-\d+$/', $this->makeComponent(['name' => 'user[email]'])->id());
        $this->assertMatchesRegularExpression('/^dlk-tags-\d+$/', $this->makeComponent(['name' => 'tags[]'])->id());
    }

    public function test_explicit_id_attribute_wins(): void
    {
        $component = $this->makeComponent(['id' => 'custom-id', 'name' => 'email']);

        $this->assertSame('custom-id', $component->id());
        $this->assertSame('custom-id-error', $component->errorId());
    }

    public function test_id_is_stable_across_multiple_calls(): void
    {
        $component = $this->makeComponent();

        $this->assertSame($component->id(), $component->id());
    }

    public function test_ids_are_unique_across_instances_with_same_name(): void
    {
        $first = $this->makeComponent(['name' => 'email']);
        $second = $this->makeComponent(['name' => 'email']);

        $this->assertNotSame($first->id(), $second->id());
    }

    public function test_id_falls_back_to_class_name_and_unique_suffix(): void
    {
        $this->assertMatchesRegularExpression('/^dlk-dummy-component-\d+$/', $this->makeComponent()->id());
    }

    public function test_error_and_hint_ids_derive_from_id(): void
    {
        $component = $this->makeComponent(['name' => 'email']);

        $this->assertSame($component->id() . '-error', $component->errorId());
        $this->assertSame($component->id() . '-hint', $component->hintId());
    }

    public function test_no_errors_by_default(): void
    {
        $component = $this->makeComponent(['name' => 'email']);

        $this->assertFalse($component->hasError());
        $this->assertNull($component->errorMessage());
        $this->assertSame([], $component->ariaAttributes());
    }

    public function test_binds_shared_errors(): void
    {
        $this->shareErrors(['email' => 'The email field is required.']);

        $component = $this->makeComponent(['name' => 'email']);

        $this->assertTrue($component->hasError());
        $this->assertSame('The email field is required.', $component->errorMessage());
    }

    public function test_error_lookup_uses_dot_notation(): void
    {
        $this->shareErrors(['user.email' => 'The email is invalid.']);

        $component = $this->makeComponent(['name' => 'user[email]']);

        $this->assertTrue($component->hasError());
        $this->assertSame('The email is invalid.', $component->errorMessage());
    }

    public function test_overridden_id_seed_does_not_break_error_binding(): void
    {
        $this->shareErrors(['roles' => 'The roles field is required.']);

        $component = new DummyCheckboxComponent;
        $component->attributes = new ComponentAttributeBag(['name' => 'roles[]', 'value' => 'admin']);

        $this->assertTrue($component->hasError());
        $this->assertSame('The roles field is required.', $component->errorMessage());
        $this->assertMatchesRegularExpression('/^dlk-roles-admin-\d+$/', $component->id());
    }

    public function test_aria_attributes_on_error(): void
    {
        $this->shareErrors(['email' => 'The email field is required.']);

        $component = $this->makeComponent(['name' => 'email', 'required' => true]);

        $this->assertSame([
            'aria-invalid' => 'true',
            'aria-required' => 'true',
            'aria-describedby' => $component->errorId(),
        ], $component->ariaAttributes());
    }

    public function test_described_by_includes_hint_and_error(): void
    {
        $this->shareErrors(['email' => 'The email field is required.']);

        $component = new DummyHintComponent;
        $component->attributes = new ComponentAttributeBag(['name' => 'email']);

        $this->assertSame(
            $component->hintId() . ' ' . $component->errorId(),
            $component->describedBy()
        );
    }

    public function test_component_without_name_has_no_error_state(): void
    {
        $this->shareErrors(['email' => 'The email field is required.']);

        $component = $this->makeComponent();

        $this->assertFalse($component->hasError());
        $this->assertNull($component->errorMessage());
        $this->assertSame([], $component->ariaAttributes());
    }

    private function shareErrors(array $messages): void
    {
        View::share('errors', (new ViewErrorBag)->put('default', new MessageBag($messages)));
    }
}
