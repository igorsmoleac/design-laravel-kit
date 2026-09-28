<?php

namespace IgorSmoleac\DesignLaravelKit\Tests\Feature;

use IgorSmoleac\DesignLaravelKit\DesignLaravelKitServiceProvider;
use Orchestra\Testbench\TestCase;

class RadioGroupComponentTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [DesignLaravelKitServiceProvider::class];
    }

    public function test_radio_group_renders_one_validation_error_for_nested_radios(): void
    {
        $this->withViewErrors(['gender' => 'Seleziona un genere.']);

        $html = $this->blade('<x-italia::radio-group name="gender" legend="Genere"><x-italia::radio name="gender" value="m" /><x-italia::radio name="gender" value="f" /></x-italia::radio-group>');

        $html->assertSee('<fieldset', false)
            ->assertSee('Genere')
            ->assertSee('type="radio"', false)
            ->assertSee('Seleziona un genere.', false)
            ->assertDontSee('class="invalid-feedback"', false);
    }
}
