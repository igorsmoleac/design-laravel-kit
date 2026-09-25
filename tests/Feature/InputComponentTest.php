<?php

namespace IgorSmoleac\DesignLaravelKit\Tests\Feature;

use IgorSmoleac\DesignLaravelKit\DesignLaravelKitServiceProvider;
use Orchestra\Testbench\TestCase;

class InputComponentTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [DesignLaravelKitServiceProvider::class];
    }

    public function test_renders_error_from_session_after_failed_validation(): void
    {
        $this->withViewErrors(['email' => 'The email field is required.']);

        $this->blade('<x-italia::input name="email" />')
            ->assertSee('is-invalid', false)
            ->assertSee('aria-invalid="true"', false)
            ->assertSee('The email field is required.')
            ->assertSee('role="alert"', false);
    }

    public function test_renders_old_input_after_failed_validation(): void
    {
        $this->withViewErrors(['email' => 'The email is invalid.']);
        $this->withSession(['_old_input' => ['email' => 'vecchio@example.com']]);

        $this->blade('<x-italia::input name="email" />')
            ->assertSee('value="vecchio@example.com"', false);
    }

    public function test_renders_label_bound_to_input(): void
    {
        $this->blade('<x-italia::input name="email" label="Email" />')
            ->assertSee('for="dlk-email', false);
    }
}
