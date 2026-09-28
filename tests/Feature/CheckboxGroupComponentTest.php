<?php

namespace IgorSmoleac\DesignLaravelKit\Tests\Feature;

use IgorSmoleac\DesignLaravelKit\DesignLaravelKitServiceProvider;
use Orchestra\Testbench\TestCase;

class CheckboxGroupComponentTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [DesignLaravelKitServiceProvider::class];
    }

    public function test_checkbox_group_renders_one_validation_error_for_nested_checkboxes(): void
    {
        $this->withViewErrors(['interests' => 'Seleziona almeno un interesse.']);

        $html = $this->blade('<x-italia::checkbox-group name="interests" legend="Interessi"><x-italia::checkbox name="interests[]" value="sport" /><x-italia::checkbox name="interests[]" value="music" /></x-italia::checkbox-group>');

        $html->assertSee('<fieldset', false)
            ->assertSee('Interessi')
            ->assertSee('type="checkbox"', false)
            ->assertSee('Seleziona almeno un interesse.', false)
            ->assertDontSee('class="invalid-feedback"', false);
    }
}
