<?php

namespace IgorSmoleac\DesignLaravelKit\Tests\Feature;

use IgorSmoleac\DesignLaravelKit\DesignLaravelKitServiceProvider;
use Orchestra\Testbench\TestCase;

class CheckboxComponentTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [DesignLaravelKitServiceProvider::class];
    }

    public function test_renders_error_from_session_after_failed_validation(): void
    {
        $this->withViewErrors(['terms' => 'Devi accettare i termini.']);

        $this->blade('<x-italia::checkbox name="terms" />')
            ->assertSee('is-invalid', false)
            ->assertSee('aria-invalid="true"', false)
            ->assertSee('Devi accettare i termini.')
            ->assertSee('role="alert"', false);
    }

    public function test_renders_checked_state_from_old_input_after_failed_validation(): void
    {
        $this->withSession(['_old_input' => ['terms' => '1']]);

        $this->blade('<x-italia::checkbox name="terms" value="1" />')
            ->assertSee('checked="checked"', false);
    }

    public function test_renders_label_bound_to_input(): void
    {
        $this->blade('<x-italia::checkbox name="terms" label="Accetto i termini" />')
            ->assertSee('for="dlk-terms', false);
    }
}
