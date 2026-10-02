<?php

namespace IgorSmoleac\DesignLaravelKit\Tests\Unit\Components;

use IgorSmoleac\DesignLaravelKit\DesignLaravelKitServiceProvider;
use Illuminate\View\ViewException;
use Orchestra\Testbench\TestCase;

class HeaderSlimTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [DesignLaravelKitServiceProvider::class];
    }

    public function test_renders_entity_and_login_from_named_attributes(): void
    {
        $html = (string) $this->blade('<x-italia::header-slim ente="Comune di Roma" ente-url="https://www.comune.roma.it" login-url="/login" />');

        $this->assertStringContainsString('it-header-slim-wrapper', $html);
        $this->assertStringContainsString('href="https://www.comune.roma.it">Comune di Roma</a>', $html);
        $this->assertStringContainsString('href="/login"', $html);
    }

    public function test_default_entity_and_optional_login_are_rendered(): void
    {
        $html = (string) $this->blade('<x-italia::header-slim />');

        $this->assertStringContainsString('Ente appartenenza', $html);
        $this->assertStringNotContainsString('it-access-top-wrapper', $html);
        $this->assertStringNotContainsString('nav-mobile', $html);
    }

    public function test_links_slot_renders_in_mobile_menu(): void
    {
        $html = (string) $this->blade(<<<'BLADE'
<x-italia::header-slim ente="Comune">
    <x-slot:links>
        <x-italia::header-nav-item text="Home" url="/" active />
        <x-italia::header-nav-item text="Contatti" url="/contatti" />
    </x-slot:links>
</x-italia::header-slim>
BLADE);

        $this->assertStringContainsString('nav-mobile', $html);
        $this->assertStringContainsString('href="/"', $html);
        $this->assertStringContainsString('aria-current="page"', $html);
        $this->assertStringContainsString('href="/contatti"', $html);
    }

    public function test_languages_slot_renders_dropdown_and_custom_label(): void
    {
        $html = (string) $this->blade(<<<'BLADE'
<x-italia::header-slim language-label="ITA">
    <x-slot:languages><li><a class="dropdown-item list-item" href="/en">ENG</a></li></x-slot:languages>
</x-italia::header-slim>
BLADE);

        $this->assertStringContainsString('nav-link dropdown-toggle', $html);
        $this->assertStringContainsString('>ITA</span>', $html);
        $this->assertStringContainsString('href="/en">ENG</a>', $html);
    }

    public function test_old_array_attributes_are_rejected(): void
    {
        $this->expectException(ViewException::class);
        $this->expectExceptionMessage("The 'links' array attribute is no longer supported");

        $this->blade('<x-italia::header-slim :links="[[\'url\' => \'/\', \'text\' => \'Home\']]" />');
    }

    public function test_custom_options_and_id_are_rendered(): void
    {
        $html = (string) $this->blade('<x-italia::header-slim id="slim" light sticky login-url="/login" login-label="Entra"><x-slot:links><li><a href="/">Home</a></li></x-slot:links></x-italia::header-slim>');

        $this->assertStringContainsString('it-header-slim-wrapper theme-light', $html);
        $this->assertStringContainsString('data-bs-toggle="sticky"', $html);
        $this->assertStringContainsString('id="slim-mobile-menu"', $html);
        $this->assertStringContainsString('Entra', $html);
    }

    public function test_invalid_login_url_is_rejected(): void
    {
        $this->expectException(ViewException::class);
        $this->expectExceptionMessage("Field 'loginUrl'");

        $this->blade('<x-italia::header-slim login-url="invalid url" />');
    }

    public function test_mobile_menu_controls_existing_menu_id(): void
    {
        $html = (string) $this->blade('<x-italia::header-slim id="top" links><x-slot:links><li>Link</li></x-slot:links></x-italia::header-slim>');

        $this->assertStringContainsString('id="top-mobile-menu"', $html);
        $this->assertStringContainsString('aria-controls="top-mobile-menu"', $html);
        $this->assertStringContainsString('href="#top-mobile-menu"', $html);
    }

    public function test_right_zone_renders_with_login_only(): void
    {
        $html = (string) $this->blade('<x-italia::header-slim login-url="/login" />');

        $this->assertStringContainsString('it-header-slim-right-zone', $html);
        $this->assertStringContainsString('href="/login"', $html);
        $this->assertStringNotContainsString('dropdown-menu', $html);
    }

    public function test_ente_renders_as_escaped_text_when_url_is_absent(): void
    {
        $html = (string) $this->blade('<x-italia::header-slim ente="<script>Comune</script>" />');

        $this->assertStringContainsString('&lt;script&gt;Comune&lt;/script&gt;', $html);
        $this->assertStringNotContainsString('<script>Comune</script>', $html);
        $this->assertStringNotContainsString('navbar-brand" href=', $html);
    }

    public function test_empty_links_and_languages_slots_render_no_optional_navigation(): void
    {
        $html = (string) $this->blade(<<<'BLADE'
<x-italia::header-slim>
    <x-slot:links></x-slot:links>
    <x-slot:languages></x-slot:languages>
</x-italia::header-slim>
BLADE);

        $this->assertStringNotContainsString('nav-mobile', $html);
        $this->assertStringNotContainsString('dropdown-menu', $html);
        $this->assertStringNotContainsString('it-header-slim-right-zone', $html);
    }

    public function test_links_slot_escapes_helper_text_and_uses_accessible_navigation_label(): void
    {
        $html = (string) $this->blade(<<<'BLADE'
<x-italia::header-slim ente="Comune">
    <x-slot:links><x-italia::header-nav-item text="<script>Servizi</script>" url="/servizi" /></x-slot:links>
</x-italia::header-slim>
BLADE);

        $this->assertStringContainsString('aria-label="Navigazione accessoria"', $html);
        $this->assertStringContainsString('&lt;script&gt;Servizi&lt;/script&gt;', $html);
        $this->assertStringNotContainsString('<script>Servizi</script>', $html);
    }

    public function test_two_instances_have_distinct_mobile_menu_ids(): void
    {
        $html = (string) $this->blade('<x-italia::header-slim id="first"><x-slot:links><li>One</li></x-slot:links></x-italia::header-slim><x-italia::header-slim id="second"><x-slot:links><li>Two</li></x-slot:links></x-italia::header-slim>');

        $this->assertStringContainsString('id="first-mobile-menu"', $html);
        $this->assertStringContainsString('id="second-mobile-menu"', $html);
        $this->assertStringContainsString('aria-controls="first-mobile-menu"', $html);
        $this->assertStringContainsString('aria-controls="second-mobile-menu"', $html);
    }

    public function test_languages_slot_includes_accessible_selected_language_label(): void
    {
        $html = (string) $this->blade('<x-italia::header-slim language-label="ITA"><x-slot:languages><li>English</li></x-slot:languages></x-italia::header-slim>');

        $this->assertStringContainsString('aria-expanded="false"', $html);
        $this->assertStringContainsString('lingua selezionata', $html);
        $this->assertStringContainsString('>ITA</span>', $html);
        $this->assertStringContainsString('English', $html);
    }

    public function test_custom_id_and_data_attributes_are_forwarded_to_wrapper(): void
    {
        $html = (string) $this->blade('<x-italia::header-slim id="slim-header" data-element="slim" />');

        $this->assertStringContainsString('id="slim-header"', $html);
        $this->assertStringContainsString('data-element="slim"', $html);
    }

    public function test_ente_url_takes_precedence_over_plain_text_rendering(): void
    {
        $html = (string) $this->blade('<x-italia::header-slim ente="Comune" ente-url="/comune" />');

        $this->assertStringContainsString('navbar-brand" href="/comune">Comune</a>', $html);
        $this->assertStringNotContainsString('<span class="d-none d-lg-block navbar-brand">Comune</span>', $html);
    }

    public function test_custom_login_label_is_visible_and_login_zone_is_accessible(): void
    {
        $html = (string) $this->blade('<x-italia::header-slim login-url="/login" login-label="Area riservata" />');

        $this->assertStringContainsString('it-header-slim-right-zone', $html);
        $this->assertStringContainsString('Area riservata', $html);
        $this->assertStringContainsString('href="/login"', $html);
    }

    public function test_links_and_languages_can_render_together(): void
    {
        $html = (string) $this->blade('<x-italia::header-slim><x-slot:links><li>Servizi</li></x-slot:links><x-slot:languages><li>English</li></x-slot:languages></x-italia::header-slim>');

        $this->assertStringContainsString('nav-mobile', $html);
        $this->assertStringContainsString('dropdown-menu', $html);
        $this->assertStringContainsString('Servizi', $html);
        $this->assertStringContainsString('English', $html);
    }
}
