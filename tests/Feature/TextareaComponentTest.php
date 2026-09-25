<?php

namespace IgorSmoleac\DesignLaravelKit\Tests\Feature;

use IgorSmoleac\DesignLaravelKit\DesignLaravelKitServiceProvider;
use Orchestra\Testbench\TestCase;

class TextareaComponentTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [DesignLaravelKitServiceProvider::class];
    }

    public function test_renders_error_from_session_after_failed_validation(): void
    {
        $this->withViewErrors(['bio' => 'The bio field is required.']);

        $this->blade('<x-italia::textarea name="bio" />')
            ->assertSee('is-invalid', false)
            ->assertSee('aria-invalid="true"', false)
            ->assertSee('The bio field is required.')
            ->assertSee('role="alert"', false);
    }

    public function test_renders_old_input_after_failed_validation(): void
    {
        $this->withViewErrors(['bio' => 'The bio is too long.']);
        $this->withSession(['_old_input' => ['bio' => 'Vecchia biografia']]);

        $this->blade('<x-italia::textarea name="bio" />')
            ->assertSee('>Vecchia biografia</textarea>', false);
    }

    public function test_renders_label_bound_to_textarea(): void
    {
        $this->blade('<x-italia::textarea name="bio" label="Biografia" />')
            ->assertSee('for="dlk-bio', false);
    }
}
