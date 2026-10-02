<?php

namespace IgorSmoleac\DesignLaravelKit\Tests\Unit\Components;

use IgorSmoleac\DesignLaravelKit\DesignLaravelKitServiceProvider;
use Illuminate\View\ViewException;
use Orchestra\Testbench\TestCase;

class HeaderMegamenuTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [DesignLaravelKitServiceProvider::class];
    }

    public function test_megamenu_renders_accessible_toggle_and_slotted_sections(): void
    {
        $html = (string) $this->blade(<<<'BLADE'
<x-italia::header-navbar>
    <x-italia::header-megamenu text="Servizi" url="/servizi" active>
        <x-italia::header-megamenu-section heading="Anagrafe">
            <x-italia::header-nav-item text="Certificati" url="/certificati" />
        </x-italia::header-megamenu-section>
    </x-italia::header-megamenu>
</x-italia::header-navbar>
BLADE);

        $this->assertStringContainsString('class="nav-item dropdown megamenu"', $html);
        $this->assertStringContainsString('class="nav-link dropdown-toggle active"', $html);
        $this->assertStringContainsString('href="/servizi"', $html);
        $this->assertStringContainsString('aria-expanded="false"', $html);
        $this->assertStringContainsString('aria-current="page"', $html);
        $this->assertStringContainsString('class="dropdown-menu" id="dlk-header-megamenu-', $html);
        $this->assertStringContainsString('aria-controls="dlk-header-megamenu-', $html);
        $this->assertStringContainsString('Anagrafe', $html);
        $this->assertStringContainsString('href="/certificati"', $html);
    }

    public function test_multiple_megamenu_ids_are_unique(): void
    {
        $html = (string) $this->blade(<<<'BLADE'
<x-italia::header-navbar>
    <x-italia::header-megamenu text="Servizi"><x-italia::header-megamenu-section heading="Anagrafe"></x-italia::header-megamenu-section></x-italia::header-megamenu>
    <x-italia::header-megamenu text="Temi"><x-italia::header-megamenu-section heading="Tributi"></x-italia::header-megamenu-section></x-italia::header-megamenu>
</x-italia::header-navbar>
BLADE);
        preg_match_all('/class="dropdown-menu" id="(dlk-header-megamenu-[^"]+)"/', $html, $matches);

        $this->assertCount(2, $matches[1]);
        $this->assertNotSame($matches[1][0], $matches[1][1]);
    }

    public function test_missing_megamenu_url_defaults_to_hash(): void
    {
        $html = (string) $this->blade('<x-italia::header-navbar><x-italia::header-megamenu text="Servizi" /></x-italia::header-navbar>');

        $this->assertStringContainsString('href="#"', $html);
    }

    public function test_empty_megamenu_text_is_rejected(): void
    {
        $this->expectException(ViewException::class);
        $this->expectExceptionMessage("Field 'text'");

        $this->blade('<x-italia::header-navbar><x-italia::header-megamenu text="" /></x-italia::header-navbar>');
    }

    public function test_invalid_megamenu_url_is_rejected(): void
    {
        $this->expectException(ViewException::class);
        $this->expectExceptionMessage("Field 'url'");

        $this->blade('<x-italia::header-navbar><x-italia::header-megamenu text="Servizi" url="javascript:alert(1)" /></x-italia::header-navbar>');
    }

    public function test_array_based_megamenu_configuration_is_rejected(): void
    {
        $this->expectException(ViewException::class);
        $this->expectExceptionMessage("The 'megamenu' array attribute is no longer supported");

        $this->blade('<x-italia::header-navbar><x-italia::header-megamenu text="Servizi" :megamenu="[]" /></x-italia::header-navbar>');
    }
}
