<?php

namespace IgorSmoleac\DesignLaravelKit\Tests\Feature;

use IgorSmoleac\DesignLaravelKit\DesignLaravelKitServiceProvider;
use Orchestra\Testbench\TestCase;

class BadgeComponentTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [DesignLaravelKitServiceProvider::class];
    }

    public function test_renders_pill_badge_as_link(): void
    {
        $this->blade('<x-italia::badge variant="danger" pill href="/foo">3 nuovi</x-italia::badge>')
            ->assertSee('<a class="badge rounded-pill bg-danger" href="/foo">3 nuovi</a>', false);
    }
}
