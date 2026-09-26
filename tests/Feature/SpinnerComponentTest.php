<?php

namespace IgorSmoleac\DesignLaravelKit\Tests\Feature;

use IgorSmoleac\DesignLaravelKit\DesignLaravelKitServiceProvider;
use Orchestra\Testbench\TestCase;

class SpinnerComponentTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [DesignLaravelKitServiceProvider::class];
    }

    public function test_renders_labelled_spinner_with_status_role(): void
    {
        $this->blade('<x-italia::spinner size="xl" label="Caricamento in corso" />')
            ->assertSee('progress-spinner progress-spinner-active progress-spinner-double size-xl', false)
            ->assertSee('role="status"', false)
            ->assertSee('aria-label="Caricamento in corso"', false);
    }
}
