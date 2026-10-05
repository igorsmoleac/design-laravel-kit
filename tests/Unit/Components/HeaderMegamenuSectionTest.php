<?php

namespace IgorSmoleac\DesignLaravelKit\Tests\Unit\Components;

use IgorSmoleac\DesignLaravelKit\Components\HeaderMegamenuSection;
use IgorSmoleac\DesignLaravelKit\DesignLaravelKitServiceProvider;
use Illuminate\View\ViewException;
use Orchestra\Testbench\TestCase;

class HeaderMegamenuSectionTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [DesignLaravelKitServiceProvider::class];
    }

    public function test_section_heading_and_navigation_items_render_inside_column(): void
    {
        $html = (string) $this->blade(<<<'BLADE'
<x-italia::header-navbar>
    <x-italia::header-megamenu text="Servizi">
        <x-italia::header-megamenu-section heading="Anagrafe">
            <x-italia::header-nav-item text="Certificati" url="/certificati" />
        </x-italia::header-megamenu-section>
    </x-italia::header-megamenu>
</x-italia::header-navbar>
BLADE);

        $this->assertStringContainsString('class="col-12 col-lg-4"', $html);
        $this->assertStringContainsString('<h3 class="link-list-heading">Anagrafe</h3>', $html);
        $this->assertStringContainsString('<ul class="link-list">', $html);
        $this->assertStringContainsString('href="/certificati"', $html);
    }

    public function test_empty_section_heading_is_rejected(): void
    {
        $this->expectException(ViewException::class);
        $this->expectExceptionMessage("Field 'heading'");

        $this->blade('<x-italia::header-megamenu text="Servizi"><x-italia::header-megamenu-section heading="" /></x-italia::header-megamenu>');
    }

    public function test_section_heading_is_escaped(): void
    {
        $html = (string) $this->blade('<x-italia::header-megamenu text="Servizi"><x-italia::header-megamenu-section heading="<script>Anagrafe</script>" /></x-italia::header-megamenu>');

        $this->assertStringContainsString('&lt;script&gt;Anagrafe&lt;/script&gt;', $html);
        $this->assertStringNotContainsString('<script>Anagrafe</script>', $html);
    }

    public function test_missing_heading_fails_during_construction(): void
    {
        $this->expectException(\ArgumentCountError::class);

        new HeaderMegamenuSection;
    }
}
