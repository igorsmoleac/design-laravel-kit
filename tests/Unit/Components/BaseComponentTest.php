<?php

namespace IgorSmoleac\DesignLaravelKit\Tests\Unit\Components;

use IgorSmoleac\DesignLaravelKit\Components\BaseComponent;
use Illuminate\Support\Str;
use Illuminate\View\ComponentAttributeBag;
use Orchestra\Testbench\TestCase;

class DummyComponent extends BaseComponent
{
    public function render(): string
    {
        return 'dummy';
    }
}

class DummyCustomIdComponent extends BaseComponent
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

    public function test_id_uses_overridden_seed(): void
    {
        $component = new DummyCustomIdComponent;
        $component->attributes = new ComponentAttributeBag(['name' => 'roles[]', 'value' => 'admin']);

        $this->assertMatchesRegularExpression('/^dlk-roles-admin-\d+$/', $component->id());
    }
}
