<?php

namespace IgorSmoleac\DesignLaravelKit\Tests\Unit\Components;

use IgorSmoleac\DesignLaravelKit\Components\HeaderNavItem;
use IgorSmoleac\DesignLaravelKit\DesignLaravelKitServiceProvider;
use Illuminate\View\ViewException;
use Orchestra\Testbench\TestCase;

class HeaderNavbarTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [DesignLaravelKitServiceProvider::class];
    }

    public function test_renders_nav_items_from_slot_components(): void
    {
        $html = (string) $this->blade(<<<'BLADE'
<x-italia::header-navbar>
    <x-italia::header-nav-item text="Home" url="/" active />
    <x-italia::header-nav-item text="Novità" url="/novita" />
</x-italia::header-navbar>
BLADE);

        $this->assertStringContainsString('it-header-navbar-wrapper', $html);
        $this->assertStringContainsString('<ul class="navbar-nav">', $html);
        $this->assertStringContainsString('nav-link active', $html);
        $this->assertStringContainsString('aria-current="page"', $html);
        $this->assertStringContainsString('href="/novita"', $html);
    }

    public function test_renders_menu_controls_and_custom_attributes(): void
    {
        $html = (string) $this->blade('<x-italia::header-navbar id="main-nav" data-element="navigation" light sticky><x-italia::header-nav-item text="Home" url="/" /></x-italia::header-navbar>');

        $this->assertStringContainsString('id="main-nav"', $html);
        $this->assertStringContainsString('data-element="navigation"', $html);
        $this->assertStringContainsString('theme-light', $html);
        $this->assertStringContainsString('data-bs-toggle="sticky"', $html);
        $this->assertStringContainsString('navbarcollapsible', $html);
    }

    public function test_empty_default_slot_renders_nothing(): void
    {
        $html = (string) $this->blade('<x-italia::header-navbar />');

        $this->assertSame('', trim($html));
    }

    public function test_old_items_array_attribute_is_rejected(): void
    {
        $this->expectException(ViewException::class);
        $this->expectExceptionMessage("The 'items' array attribute is no longer supported");

        $this->blade('<x-italia::header-navbar :items="[[\'text\' => \'Home\', \'url\' => \'/\']]" />');
    }

    public function test_nav_item_rejects_invalid_url(): void
    {
        $this->expectException(ViewException::class);
        $this->expectExceptionMessage("Field 'url'");

        $this->blade('<x-italia::header-navbar><x-italia::header-nav-item text="Home" url="bad url" /></x-italia::header-navbar>');
    }

    public function test_toggler_targets_menu_and_exposes_custom_data(): void
    {
        $html = (string) $this->blade('<x-italia::header-navbar id="nav" data-test="menu"><x-italia::header-nav-item text="Home" url="/" /></x-italia::header-navbar>');

        $this->assertStringContainsString('aria-controls="nav-menu"', $html);
        $this->assertStringContainsString('data-bs-target="#nav-menu"', $html);
        $this->assertStringContainsString('data-test="menu"', $html);
    }

    public function test_dropdown_nav_item_without_url_uses_hash_fallback(): void
    {
        $html = (string) $this->blade('<x-italia::header-navbar><x-italia::header-nav-item text="Servizi" dropdown><li><a href="/anagrafe">Anagrafe</a></li></x-italia::header-nav-item></x-italia::header-navbar>');

        $this->assertStringContainsString('class="nav-link dropdown-toggle" href="#"', $html);
    }

    public function test_nav_item_without_dropdown_still_requires_a_url(): void
    {
        $this->expectException(ViewException::class);
        $this->expectExceptionMessage("Field 'url'");

        $this->blade('<x-italia::header-navbar><x-italia::header-nav-item text="Home" /></x-italia::header-navbar>');
    }

    public function test_dropdown_nav_item_uses_provided_url_unchanged(): void
    {
        $html = (string) $this->blade('<x-italia::header-navbar><x-italia::header-nav-item text="Servizi" url="/servizi" dropdown><li><a href="/anagrafe">Anagrafe</a></li></x-italia::header-nav-item></x-italia::header-navbar>');

        $this->assertStringContainsString('class="nav-link dropdown-toggle" href="/servizi"', $html);
    }

    public function test_header_nav_item_renders_dropdown_with_nested_slot_items(): void
    {
        $html = (string) $this->blade(<<<'BLADE'
<x-italia::header-navbar>
    <x-italia::header-nav-item text="Servizi" url="/servizi" dropdown>
        <li><a class="dropdown-item list-item" href="/anagrafe">Anagrafe</a></li>
        <li><a class="dropdown-item list-item" href="/tributi">Tributi</a></li>
    </x-italia::header-nav-item>
</x-italia::header-navbar>
BLADE);

        $this->assertStringContainsString('class="nav-item dropdown"', $html);
        $this->assertStringContainsString('class="nav-link dropdown-toggle"', $html);
        $this->assertStringContainsString('data-bs-toggle="dropdown" aria-expanded="false"', $html);
        $this->assertStringContainsString('href="/anagrafe">Anagrafe</a>', $html);
        $this->assertStringContainsString('href="/tributi">Tributi</a>', $html);
    }

    public function test_header_nav_item_active_state_has_accessible_current_page_marker(): void
    {
        $html = (string) $this->blade('<x-italia::header-navbar><x-italia::header-nav-item text="Home" url="/" active /></x-italia::header-navbar>');

        $this->assertStringContainsString('class="nav-link active"', $html);
        $this->assertStringContainsString('aria-current="page"', $html);
    }

    public function test_nav_item_escapes_text_and_rejects_missing_required_fields(): void
    {
        $html = (string) $this->blade('<x-italia::header-navbar><x-italia::header-nav-item text="<script>Home</script>" url="/" /></x-italia::header-navbar>');

        $this->assertStringContainsString('&lt;script&gt;Home&lt;/script&gt;', $html);
        $this->assertStringNotContainsString('<script>Home</script>', $html);

        try {
            new HeaderNavItem(url: '/');
            $this->fail('A navigation item without text must be rejected during construction.');
        } catch (\ArgumentCountError $exception) {
            $this->assertStringContainsString('$text', $exception->getMessage());
        }
    }

    public function test_navbar_has_accessible_label_and_close_control(): void
    {
        $html = (string) $this->blade('<x-italia::header-navbar><x-italia::header-nav-item text="Home" url="/" /></x-italia::header-navbar>');

        $this->assertStringContainsString('aria-label="Menu principale"', $html);
        $this->assertStringContainsString('aria-label="Apri il menu"', $html);
        $this->assertStringContainsString('class="btn close-menu"', $html);
    }

    public function test_two_navbars_have_distinct_menu_ids(): void
    {
        $html = (string) $this->blade('<x-italia::header-navbar id="primary"><x-italia::header-nav-item text="Home" url="/" /></x-italia::header-navbar><x-italia::header-navbar id="secondary"><x-italia::header-nav-item text="Home" url="/" /></x-italia::header-navbar>');

        $this->assertStringContainsString('id="primary-menu"', $html);
        $this->assertStringContainsString('aria-controls="primary-menu"', $html);
        $this->assertStringContainsString('id="secondary-menu"', $html);
        $this->assertStringContainsString('aria-controls="secondary-menu"', $html);
    }

    public function test_navbar_empty_named_slot_omits_wrapper(): void
    {
        $html = (string) $this->blade('<x-italia::header-navbar />');

        $this->assertStringNotContainsString('it-header-navbar-wrapper', $html);
    }

    public function test_dropdown_can_contain_multiple_nested_links_in_declared_order(): void
    {
        $html = (string) $this->blade('<x-italia::header-navbar><x-italia::header-nav-item text="Servizi" url="/servizi" dropdown><li><a href="/anagrafe">Anagrafe</a></li><li><a href="/tributi">Tributi</a></li></x-italia::header-nav-item></x-italia::header-navbar>');

        $this->assertLessThan(strpos($html, 'href="/tributi"'), strpos($html, 'href="/anagrafe"'));
        $this->assertStringContainsString('class="dropdown-menu"', $html);
    }

    public function test_dropdown_parent_text_is_escaped_and_child_markup_is_preserved(): void
    {
        $html = (string) $this->blade('<x-italia::header-navbar><x-italia::header-nav-item text="<script>Menu</script>" url="/menu" dropdown><li><a href="/child">Child</a></li></x-italia::header-nav-item></x-italia::header-navbar>');

        $this->assertStringContainsString('&lt;script&gt;Menu&lt;/script&gt;', $html);
        $this->assertStringNotContainsString('<script>Menu</script>', $html);
        $this->assertStringContainsString('<a href="/child">Child</a>', $html);
    }

    public function test_megamenu_adds_special_nav_class(): void
    {
        $html = (string) $this->blade('<x-italia::header-navbar><x-italia::header-megamenu text="Servizi"><x-italia::header-megamenu-section heading="Anagrafe"></x-italia::header-megamenu-section></x-italia::header-megamenu></x-italia::header-navbar>');

        $this->assertStringContainsString('class="navbar navbar-expand-lg has-megamenu"', $html);
    }

    public function test_plain_nav_item_with_megamenu_text_does_not_add_special_nav_class(): void
    {
        $html = (string) $this->blade('<x-italia::header-navbar><x-italia::header-nav-item text="dropdown megamenu" url="/menu" /></x-italia::header-navbar>');

        $this->assertStringContainsString('class="navbar navbar-expand-lg"', $html);
        $this->assertStringNotContainsString('has-megamenu', $html);
    }

    public function test_multiple_megamenus_add_special_nav_class_once(): void
    {
        $html = (string) $this->blade(<<<'BLADE'
<x-italia::header-navbar>
    <x-italia::header-megamenu text="Servizi" url="/servizi">
        <x-italia::header-megamenu-section heading="Anagrafe"></x-italia::header-megamenu-section>
    </x-italia::header-megamenu>
    <x-italia::header-megamenu text="Temi" url="/temi">
        <x-italia::header-megamenu-section heading="Ambiente"></x-italia::header-megamenu-section>
    </x-italia::header-megamenu>
</x-italia::header-navbar>
BLADE);

        $this->assertSame(1, substr_count($html, 'navbar navbar-expand-lg has-megamenu'));
        $this->assertStringContainsString('class="nav-item dropdown megamenu"', $html);
    }

    public function test_navbar_without_megamenu_has_no_special_nav_class(): void
    {
        $html = (string) $this->blade('<x-italia::header-navbar><x-italia::header-nav-item text="Servizi" url="/servizi" /></x-italia::header-navbar>');

        $this->assertStringContainsString('class="navbar navbar-expand-lg"', $html);
        $this->assertStringNotContainsString('navbar navbar-expand-lg has-megamenu', $html);
    }

    public function test_megamenu_detection_preserves_mixed_nav_item_types(): void
    {
        $html = (string) $this->blade(<<<'BLADE'
<x-italia::header-navbar>
    <x-italia::header-nav-item text="Home" url="/" active />
    <x-italia::header-nav-item text="Servizi" url="/servizi" dropdown>
        <li><a class="dropdown-item list-item" href="/anagrafe">Anagrafe</a></li>
    </x-italia::header-nav-item>
    <x-italia::header-megamenu text="Temi" url="/temi">
        <x-italia::header-megamenu-section heading="Ambiente">
            <x-italia::header-nav-item text="Verde" url="/verde" />
        </x-italia::header-megamenu-section>
    </x-italia::header-megamenu>
</x-italia::header-navbar>
BLADE);

        $this->assertStringContainsString('class="navbar navbar-expand-lg has-megamenu"', $html);
        $this->assertStringContainsString('class="nav-link active"', $html);
        $this->assertStringContainsString('class="nav-item dropdown"', $html);
        $this->assertStringContainsString('class="nav-item dropdown megamenu"', $html);
        $this->assertStringContainsString('href="/verde"', $html);
    }
}
