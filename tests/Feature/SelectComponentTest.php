<?php

namespace IgorSmoleac\DesignLaravelKit\Tests\Feature;

use IgorSmoleac\DesignLaravelKit\DesignLaravelKitServiceProvider;
use Orchestra\Testbench\TestCase;

class SelectComponentTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [DesignLaravelKitServiceProvider::class];
    }

    public function test_renders_error_from_session_after_failed_validation(): void
    {
        $this->withViewErrors(['country' => 'The country field is required.']);

        $this->blade('<x-italia::select name="country" />')
            ->assertSee('is-invalid', false)
            ->assertSee('aria-invalid="true"', false)
            ->assertSee('The country field is required.')
            ->assertSee('role="alert"', false);
    }

    public function test_renders_old_input_after_failed_validation(): void
    {
        $this->withViewErrors(['country' => 'The country is invalid.']);
        $this->withSession(['_old_input' => ['country' => 'fr']]);

        $this->blade('<x-italia::select name="country" :options="[\'it\' => \'Italia\', \'fr\' => \'Francia\']" />')
            ->assertSee('<option value="fr" selected>Francia</option>', false);
    }

    public function test_renders_label_bound_to_select(): void
    {
        $this->blade('<x-italia::select name="country" label="Paese" />')
            ->assertSee('for="dlk-country', false);
    }
}
