<?php

namespace IgorSmoleac\DesignLaravelKit\Tests\Feature;

use IgorSmoleac\DesignLaravelKit\DesignLaravelKitServiceProvider;
use Orchestra\Testbench\TestCase;

class RadioComponentTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [DesignLaravelKitServiceProvider::class];
    }

    public function test_renders_error_from_session_after_failed_validation(): void
    {
        $this->withViewErrors(['gender' => 'Seleziona una opzione.']);

        $this->blade('<x-italia::radio name="gender" value="m" />')
            ->assertSee('is-invalid', false)
            ->assertSee('aria-invalid="true"', false)
            ->assertSee('Seleziona una opzione.')
            ->assertSee('role="alert"', false);
    }

    public function test_renders_checked_state_from_old_input_after_failed_validation(): void
    {
        $this->withSession(['_old_input' => ['gender' => 'f']]);

        $this->blade('<x-italia::radio name="gender" value="f" />')
            ->assertSee('checked="checked"', false);
    }

    public function test_renders_label_bound_to_input(): void
    {
        $this->blade('<x-italia::radio name="gender" value="m" label="Maschio" />')
            ->assertSee('for="dlk-gender', false);
    }
}
