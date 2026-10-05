<?php

namespace IgorSmoleac\DesignLaravelKit\Tests\Unit\Components;

use IgorSmoleac\DesignLaravelKit\DesignLaravelKitServiceProvider;
use Illuminate\View\ViewException;
use Orchestra\Testbench\TestCase;

class LayoutTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [DesignLaravelKitServiceProvider::class];
    }

    public function test_renders_layout_from_named_slots(): void
    {
        $html = (string) $this->blade(<<<'BLADE'
<x-italia::layout title="Comune di Roma">
    <x-slot:slim ente="Comune di Roma" ente-url="https://www.comune.roma.it" login-url="/login"></x-slot:slim>
    <x-slot:center title="Comune di Roma" tagline="Portale istituzionale" url="/" search-url="/cerca"></x-slot:center>
    <x-slot:navbar>
        <x-italia::header-nav-item text="Servizi" url="/servizi" />
        <x-italia::header-nav-item text="Amministrazione" url="/amministrazione" />
    </x-slot:navbar>
    <x-slot:footer title="Comune di Roma">
        <x-italia::footer-legal-link url="/privacy" text="Privacy policy" data-element="privacy-policy-link" />
    </x-slot:footer>
    <h1>Contenuto</h1>
</x-italia::layout>
BLADE);

        $this->assertStringContainsString('<!DOCTYPE html>', $html);
        $this->assertStringContainsString('Comune di Roma', $html);
        $this->assertStringContainsString('href="/servizi"', $html);
        $this->assertStringContainsString('href="/amministrazione"', $html);
        $this->assertStringContainsString('Portale istituzionale', $html);
        $this->assertStringContainsString('data-element="privacy-policy-link"', $html);
        $this->assertStringContainsString('<h1>Contenuto</h1>', $html);
    }

    public function test_slots_render_without_configuration_when_omitted(): void
    {
        config(['app.name' => 'Portale PA']);

        $html = (string) $this->blade('<x-italia::layout title="Home" />');

        $this->assertStringContainsString('<title>Home</title>', $html);
        $this->assertStringContainsString('<h2 class="no_toc">Portale PA</h2>', $html);
        $this->assertStringNotContainsString('it-header-slim-wrapper', $html);
        $this->assertStringNotContainsString('it-header-center-wrapper', $html);
        $this->assertStringNotContainsString('it-header-navbar-wrapper', $html);
    }

    public function test_old_array_attributes_are_rejected(): void
    {
        $this->expectException(ViewException::class);
        $this->expectExceptionMessage("The 'slim' array attribute is no longer supported");

        $this->blade('<x-italia::layout title="Home" :slim="[\'ente\' => \'Comune di Roma\']" />');
    }

    public function test_missing_required_slim_slot_attribute_names_field(): void
    {
        $this->expectException(ViewException::class);
        $this->expectExceptionMessage("Field 'ente'");

        $this->blade('<x-italia::layout><x-slot:slim></x-slot:slim></x-italia::layout>');
    }

    public function test_invalid_slot_url_names_field(): void
    {
        $this->expectException(ViewException::class);
        $this->expectExceptionMessage("Field 'enteUrl'");

        $this->blade('<x-italia::layout><x-slot:slim ente="Comune" ente-url="not a url"></x-slot:slim></x-italia::layout>');
    }

    public function test_skip_link_can_be_disabled_and_customized(): void
    {
        $html = (string) $this->blade('<x-italia::layout skip-label="Salta al contenuto" :skip-to-content="false" />');

        $this->assertStringNotContainsString('visually-hidden-focusable', $html);
        $this->assertStringNotContainsString('Salta al contenuto', $html);
    }

    public function test_main_class_and_breadcrumb_slot_are_rendered(): void
    {
        $html = (string) $this->blade(<<<'BLADE'
<x-italia::layout main-class="container-fluid px-0">
    <x-slot:breadcrumbs><ol class="breadcrumb"><li>Home</li></ol></x-slot:breadcrumbs>
    <h1>Benvenuto</h1>
</x-italia::layout>
BLADE);

        $this->assertStringContainsString('<main id="main" class="container-fluid px-0">', $html);
        $this->assertStringContainsString('<h1>Benvenuto</h1>', $html);
        $this->assertStringContainsString('breadcrumb', $html);
    }

    public function test_layout_attributes_and_assets_are_rendered(): void
    {
        $html = (string) $this->blade('<x-italia::layout body-class="it-page-custom" description="Descrizione" />');

        $this->assertStringContainsString('<body class="bg-white it-page-custom">', $html);
        $this->assertStringContainsString('<meta name="description" content="Descrizione">', $html);
        $this->assertStringContainsString('design-laravel-kit.css', $html);
        $this->assertStringContainsString('design-laravel-kit.js', $html);
    }

    public function test_empty_title_falls_back_to_app_name_and_locale(): void
    {
        config(['app.name' => 'Portale PA', 'app.locale' => 'it']);

        $html = (string) $this->blade('<x-italia::layout title="" />');

        $this->assertStringContainsString('<title>Portale PA</title>', $html);
        $this->assertStringContainsString('<html lang="it">', $html);
    }

    public function test_skip_link_uses_default_accessible_label(): void
    {
        $html = (string) $this->blade('<x-italia::layout />');

        $this->assertStringContainsString('class="visually-hidden-focusable" href="#main"', $html);
        $this->assertStringContainsString('Vai al contenuto principale', $html);
    }

    public function test_main_uses_default_container_classes(): void
    {
        $html = (string) $this->blade('<x-italia::layout />');

        $this->assertStringContainsString('<main id="main" class="container my-4">', $html);
    }

    public function test_csrf_meta_tag_and_description_are_optional(): void
    {
        $html = (string) $this->blade('<x-italia::layout />');

        $this->assertStringContainsString('<meta name="csrf-token"', $html);
        $this->assertStringNotContainsString('name="description"', $html);
    }

    public function test_light_is_forwarded_to_header_and_footer(): void
    {
        $html = (string) $this->blade(<<<'BLADE'
<x-italia::layout light>
    <x-slot:slim ente="Comune"></x-slot:slim>
    <x-slot:center title="Comune"></x-slot:center>
    <x-slot:navbar><x-italia::header-nav-item text="Home" url="/" /></x-slot:navbar>
</x-italia::layout>
BLADE);

        $this->assertStringContainsString('it-header-wrapper theme-light', $html);
        $this->assertStringContainsString('it-header-slim-wrapper theme-light', $html);
        $this->assertStringContainsString('it-header-center-wrapper theme-light', $html);
        $this->assertStringContainsString('it-header-navbar-wrapper theme-light', $html);
        $this->assertStringContainsString('it-footer theme-light', $html);
    }

    public function test_lang_attribute_overrides_application_locale(): void
    {
        config(['app.locale' => 'it']);

        $html = (string) $this->blade('<x-italia::layout lang="en" />');

        $this->assertStringContainsString('<html lang="en">', $html);
    }

    public function test_renders_document_metadata_and_escapes_page_title(): void
    {
        $html = (string) $this->blade('<x-italia::layout title="<script>alert(1)</script>" />');

        $this->assertStringContainsString('<!DOCTYPE html>', $html);
        $this->assertStringContainsString('<meta charset="utf-8">', $html);
        $this->assertStringContainsString('<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">', $html);
        $this->assertStringContainsString('&lt;script&gt;alert(1)&lt;/script&gt;', $html);
        $this->assertStringNotContainsString('<script>alert(1)</script>', $html);
    }

    public function test_title_falls_back_to_application_name(): void
    {
        config(['app.name' => 'Portale PA']);

        $html = (string) $this->blade('<x-italia::layout />');

        $this->assertStringContainsString('<title>Portale PA</title>', $html);
    }

    public function test_description_is_optional_and_escaped_when_present(): void
    {
        $html = (string) $this->blade('<x-italia::layout description="<b>Portale</b>" />');
        $withoutDescription = (string) $this->blade('<x-italia::layout />');

        $this->assertStringContainsString('content="&lt;b&gt;Portale&lt;/b&gt;"', $html);
        $this->assertStringNotContainsString('<b>Portale</b>', $html);
        $this->assertStringNotContainsString('name="description"', $withoutDescription);
    }

    public function test_csrf_meta_tag_contains_session_token(): void
    {
        $this->withSession(['_token' => 'test-csrf-token']);

        $html = (string) $this->blade('<x-italia::layout />');

        $this->assertStringContainsString('name="csrf-token" content="test-csrf-token"', $html);
    }

    public function test_csrf_meta_tag_is_rendered_by_default(): void
    {
        $html = (string) $this->blade('<x-italia::layout />');

        $this->assertStringContainsString('<meta name="csrf-token"', $html);
    }

    public function test_csrf_meta_tag_is_removed_when_csrf_is_false(): void
    {
        $html = (string) $this->blade('<x-italia::layout :csrf="false" />');

        $this->assertStringNotContainsString('csrf-token', $html);
    }

    public function test_breadcrumb_slot_precedes_main_and_is_omitted_when_empty(): void
    {
        $html = (string) $this->blade(<<<'BLADE'
<x-italia::layout>
    <x-slot:breadcrumbs><nav aria-label="breadcrumb">Home</nav></x-slot:breadcrumbs>
</x-italia::layout>
BLADE);
        $withoutBreadcrumbs = (string) $this->blade('<x-italia::layout />');

        $this->assertLessThan(strpos($html, '<main id="main"'), strpos($html, 'aria-label="breadcrumb"'));
        $this->assertStringNotContainsString('aria-label="breadcrumb"', $withoutBreadcrumbs);
    }

    public function test_layout_body_always_has_base_class_and_accepts_custom_class(): void
    {
        $html = (string) $this->blade('<x-italia::layout body-class="it-page-custom" />');

        $this->assertStringContainsString('<body class="bg-white it-page-custom">', $html);
    }

    public function test_footer_slot_overrides_default_footer_title(): void
    {
        $html = (string) $this->blade(<<<'BLADE'
<x-italia::layout>
    <x-slot:footer title="Footer custom"></x-slot:footer>
</x-italia::layout>
BLADE);

        $this->assertStringContainsString('<h2 class="no_toc">Footer custom</h2>', $html);
    }

    public function test_named_center_slot_attributes_override_empty_title_default(): void
    {
        $html = (string) $this->blade(<<<'BLADE'
<x-italia::layout>
    <x-slot:center title="Slot title" tagline="Slot tagline"></x-slot:center>
</x-italia::layout>
BLADE);

        $this->assertStringContainsString('class="it-brand-title">Slot title</div>', $html);
        $this->assertStringContainsString('Slot tagline', $html);
    }

    public function test_skip_link_is_removed_when_disabled(): void
    {
        $html = (string) $this->blade('<x-italia::layout :skip-to-content="false" />');

        $this->assertStringNotContainsString('visually-hidden-focusable', $html);
    }

    public function test_styles_are_rendered_inside_head_before_body(): void
    {
        $html = (string) $this->blade('<x-italia::layout />');

        $this->assertLessThan(strpos($html, '<body'), strpos($html, 'design-laravel-kit.css'));
    }

    public function test_footer_slot_title_overrides_layout_footer_attributes(): void
    {
        $html = (string) $this->blade(<<<'BLADE'
<x-italia::layout
    footer-title="Layout footer"
    footer-subtitle="Portale istituzionale"
    footer-logo="/images/stemma.svg"
    footer-logo-alt="Stemma comunale"
    footer-url="/"
    footer-copyright="© 2026 Comune di Roma"
>
    <x-slot:footer title="Comune di Roma"></x-slot:footer>
</x-italia::layout>
BLADE);

        $this->assertStringContainsString('<h2 class="no_toc">Comune di Roma</h2>', $html);
        $this->assertStringNotContainsString('<h2 class="no_toc">Layout footer</h2>', $html);
        $this->assertStringContainsString('<h3 class="no_toc d-none d-md-block">Portale istituzionale</h3>', $html);
        $this->assertStringContainsString('src="/images/stemma.svg" alt="Stemma comunale"', $html);
        $this->assertStringContainsString('<a href="/">', $html);
        $this->assertStringContainsString('© 2026 Comune di Roma', $html);
    }

    public function test_footer_subtitle_attribute_is_forwarded_from_named_slot(): void
    {
        $html = (string) $this->blade('<x-italia::layout><x-slot:footer title="Comune" subtitle="Servizi ai cittadini"></x-slot:footer></x-italia::layout>');

        $this->assertStringContainsString('<h3 class="no_toc d-none d-md-block">Servizi ai cittadini</h3>', $html);
    }

    public function test_footer_logo_and_alt_attributes_are_forwarded_from_named_slot(): void
    {
        $html = (string) $this->blade('<x-italia::layout><x-slot:footer title="Comune" logo="/images/logo.svg" logo-alt="Stemma"></x-slot:footer></x-italia::layout>');

        $this->assertStringContainsString('src="/images/logo.svg" alt="Stemma"', $html);
    }

    public function test_footer_url_attribute_is_forwarded_from_named_slot(): void
    {
        $html = (string) $this->blade('<x-italia::layout><x-slot:footer title="Comune" url="/comune"></x-slot:footer></x-italia::layout>');

        $this->assertStringContainsString('<a href="/comune">', $html);
    }

    public function test_footer_copyright_attribute_is_forwarded_from_named_slot(): void
    {
        $html = (string) $this->blade('<x-italia::layout><x-slot:footer title="Comune" copyright="© Comune"></x-slot:footer></x-italia::layout>');

        $this->assertStringContainsString('© Comune', $html);
    }

    public function test_footer_sections_slot_is_forwarded_to_nested_footer(): void
    {
        $html = (string) $this->blade(<<<'BLADE'
<x-italia::layout>
    <x-slot:footer title="Comune"></x-slot:footer>
    <x-slot:footer-sections>
        <x-italia::footer-section title="Amministrazione" url="/amministrazione">
            <li><a href="/uffici">Uffici</a></li>
        </x-italia::footer-section>
    </x-slot:footer-sections>
</x-italia::layout>
BLADE);

        $this->assertStringContainsString('<a href="/amministrazione">Amministrazione</a>', $html);
        $this->assertStringContainsString('<li><a href="/uffici">Uffici</a></li>', $html);
    }

    public function test_footer_contacts_slot_is_forwarded_as_content(): void
    {
        $html = (string) $this->blade(<<<'BLADE'
<x-italia::layout>
    <x-slot:footer title="Comune"></x-slot:footer>
    <x-slot:footer-contacts>
        <div class="col-lg-4"><p>Via Roma 1</p><a href="tel:+39060000000">+39 06 0000000</a></div>
    </x-slot:footer-contacts>
</x-italia::layout>
BLADE);

        $this->assertStringContainsString('<p>Via Roma 1</p>', $html);
        $this->assertStringContainsString('href="tel:+39060000000"', $html);
    }

    public function test_footer_social_slot_is_forwarded_to_nested_footer(): void
    {
        $html = (string) $this->blade(<<<'BLADE'
<x-italia::layout>
    <x-slot:footer title="Comune"></x-slot:footer>
    <x-slot:footer-social>
        <x-italia::footer-social-link url="https://facebook.com" icon="it-facebook" label="Facebook" />
    </x-slot:footer-social>
</x-italia::layout>
BLADE);

        $this->assertStringContainsString('aria-label="Facebook"', $html);
        $this->assertStringContainsString('sprites.svg#it-facebook', $html);
    }

    public function test_footer_legal_links_slot_preserves_data_element(): void
    {
        $html = (string) $this->blade(<<<'BLADE'
<x-italia::layout>
    <x-slot:footer title="Comune"></x-slot:footer>
    <x-slot:footer-legal-links>
        <x-italia::footer-legal-link url="/privacy" text="Privacy" data-element="privacy-policy-link" />
    </x-slot:footer-legal-links>
</x-italia::layout>
BLADE);

        $this->assertStringContainsString('href="/privacy"', $html);
        $this->assertStringContainsString('data-element="privacy-policy-link"', $html);
    }

    public function test_layout_renders_without_footer_title_when_application_name_is_empty(): void
    {
        config(['app.name' => '']);

        $html = (string) $this->blade('<x-italia::layout />');

        $this->assertStringContainsString('<footer', $html);
        $this->assertStringNotContainsString('it-brand-text', $html);
    }

    public function test_layout_uses_explicit_footer_title_when_application_name_is_empty(): void
    {
        config(['app.name' => '']);

        $html = (string) $this->blade('<x-italia::layout footer-title="X" />');

        $this->assertStringContainsString('<h2 class="no_toc">X</h2>', $html);
    }

    public function test_layout_uses_laravel_as_default_footer_title(): void
    {
        config(['app.name' => 'Laravel']);

        $html = (string) $this->blade('<x-italia::layout />');

        $this->assertStringContainsString('<h2 class="no_toc">Laravel</h2>', $html);
    }

    public function test_footer_is_rendered_by_default(): void
    {
        $html = (string) $this->blade('<x-italia::layout title="Home" />');

        $this->assertStringContainsString('<footer', $html);
    }

    public function test_show_footer_false_removes_footer_but_keeps_header_and_main(): void
    {
        $html = (string) $this->blade(<<<'BLADE'
<x-italia::layout title="Login" :show-footer="false">
    <x-slot:navbar><x-italia::header-nav-item text="Home" url="/" /></x-slot:navbar>
    <h1>Accesso</h1>
</x-italia::layout>
BLADE);

        $this->assertStringNotContainsString('<footer', $html);
        $this->assertStringContainsString('it-header-navbar-wrapper', $html);
        $this->assertStringContainsString('<main id="main"', $html);
        $this->assertStringContainsString('<h1>Accesso</h1>', $html);
    }

    public function test_footer_slots_are_ignored_when_footer_is_disabled(): void
    {
        $html = (string) $this->blade(<<<'BLADE'
<x-italia::layout title="Login" :show-footer="false">
    <x-slot:footer-sections>
        <x-italia::footer-section title="Amministrazione" url="/amministrazione">
            <li><a href="/uffici">Uffici</a></li>
        </x-italia::footer-section>
    </x-slot:footer-sections>
</x-italia::layout>
BLADE);

        $this->assertStringNotContainsString('<footer', $html);
        $this->assertStringNotContainsString('/amministrazione', $html);
        $this->assertStringContainsString('design-laravel-kit.js', $html);
    }
}
