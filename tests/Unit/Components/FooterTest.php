<?php

namespace IgorSmoleac\DesignLaravelKit\Tests\Unit\Components;

use IgorSmoleac\DesignLaravelKit\Components\Footer;
use IgorSmoleac\DesignLaravelKit\Components\FooterSection;
use IgorSmoleac\DesignLaravelKit\Components\FooterSocialLink;
use IgorSmoleac\DesignLaravelKit\DesignLaravelKitServiceProvider;
use Illuminate\View\ViewException;
use Orchestra\Testbench\TestCase;

class FooterTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [DesignLaravelKitServiceProvider::class];
    }

    public function test_renders_brand_and_copyright_from_named_attributes(): void
    {
        $html = (string) $this->blade('<x-italia::footer title="Comune di Roma" subtitle="Portale istituzionale" url="/home" copyright="© 2026 Comune di Roma" />');

        $this->assertStringContainsString('class="it-footer"', $html);
        $this->assertStringContainsString('<a href="/home">', $html);
        $this->assertStringContainsString('<h2 class="no_toc">Comune di Roma</h2>', $html);
        $this->assertStringContainsString('Portale istituzionale', $html);
        $this->assertStringContainsString('© 2026 Comune di Roma', $html);
    }

    public function test_named_slots_render_sections_social_and_legal_links(): void
    {
        $html = (string) $this->blade(<<<'BLADE'
<x-italia::footer title="Comune di Roma">
    <x-slot:sections>
        <x-italia::footer-section title="Amministrazione" url="/amministrazione">
            <li><a class="list-item" href="/organi">Organi di governo</a></li>
        </x-italia::footer-section>
    </x-slot:sections>
    <x-slot:social>
        <x-italia::footer-social-link url="https://facebook.com" label="Facebook" icon="it-facebook" />
    </x-slot:social>
    <x-slot:legal-links>
        <x-italia::footer-legal-link url="/privacy" text="Privacy policy" data-element="privacy-policy-link" />
    </x-slot:legal-links>
</x-italia::footer>
BLADE);

        $this->assertStringContainsString('Amministrazione', $html);
        $this->assertStringContainsString('href="/organi"', $html);
        $this->assertStringContainsString('aria-label="Facebook"', $html);
        $this->assertStringContainsString('sprites.svg#it-facebook', $html);
        $this->assertStringContainsString('href="/privacy"', $html);
        $this->assertStringContainsString('data-element="privacy-policy-link"', $html);
    }

    public function test_default_slot_accepts_legal_link_helpers(): void
    {
        $html = (string) $this->blade(<<<'BLADE'
<x-italia::footer title="Comune di Roma">
    <x-italia::footer-legal-link url="/privacy" text="Privacy policy" />
</x-italia::footer>
BLADE);

        $this->assertStringContainsString('it-footer-small-prints-list', $html);
        $this->assertStringContainsString('href="/privacy"', $html);
    }

    public function test_footer_renders_without_title_when_application_name_is_empty(): void
    {
        config(['app.name' => '']);

        $html = (string) $this->blade('<x-italia::footer />');

        $this->assertStringContainsString('class="it-footer"', $html);
        $this->assertStringNotContainsString('it-brand-text', $html);
    }

    public function test_invalid_brand_url_is_rejected(): void
    {
        $this->expectException(ViewException::class);
        $this->expectExceptionMessage("Field 'url'");

        $this->blade('<x-italia::footer title="Comune" url="bad url" />');
    }

    public function test_old_array_attributes_are_rejected(): void
    {
        $this->expectException(ViewException::class);
        $this->expectExceptionMessage("The 'sections' array attribute is no longer supported");

        $this->blade('<x-italia::footer title="Comune" :sections="[]" />');
    }

    public function test_copyright_helpers_work_for_direct_instances(): void
    {
        $footer = new Footer(title: 'Comune di Roma');

        $this->assertSame('© ' . date('Y') . ' Comune di Roma', $footer->copyrightText());
        $this->assertSame((int) date('Y'), $footer->currentYear());
    }

    public function test_contact_slot_accepts_markup_without_array_props(): void
    {
        $html = (string) $this->blade(<<<'BLADE'
<x-italia::footer title="Comune di Roma">
    <x-slot:contacts><div class="col-lg-4"><p>Piazza del Campidoglio</p><a href="tel:+39060606">Telefono: +39 06 0606</a></div></x-slot:contacts>
</x-italia::footer>
BLADE);

        $this->assertStringContainsString('Piazza del Campidoglio', $html);
        $this->assertStringContainsString('href="tel:+39060606"', $html);
    }

    public function test_logo_alt_and_light_theme_are_preserved(): void
    {
        $html = (string) $this->blade('<x-italia::footer title="Comune" logo="/logo.svg" logo-alt="Stemma" light />');

        $this->assertStringContainsString('it-footer theme-light', $html);
        $this->assertStringContainsString('src="/logo.svg" alt="Stemma"', $html);
    }

    public function test_empty_optional_slots_are_not_rendered(): void
    {
        $html = (string) $this->blade(<<<'BLADE'
<x-italia::footer title="Comune">
    <x-slot:sections></x-slot:sections>
    <x-slot:contacts></x-slot:contacts>
    <x-slot:social></x-slot:social>
    <x-slot:legal-links></x-slot:legal-links>
</x-italia::footer>
BLADE);

        $this->assertStringNotContainsString('link-list-wrapper', $html);
        $this->assertStringNotContainsString('Seguici su', $html);
        $this->assertStringContainsString('it-footer-small-prints', $html);
        $this->assertStringContainsString('© ' . date('Y') . ' Comune', $html);
    }

    public function test_footer_section_renders_linked_and_unlinked_titles_and_escapes_content(): void
    {
        $html = (string) $this->blade(<<<'BLADE'
<x-italia::footer title="Comune">
    <x-slot:sections>
        <x-italia::footer-section title="<script>Admin</script>" url="/admin">
            <li><a href="/organi">Organi</a></li>
        </x-italia::footer-section>
        <x-italia::footer-section title="Contatti"><li>Uffici</li></x-italia::footer-section>
    </x-slot:sections>
</x-italia::footer>
BLADE);

        $this->assertStringContainsString('<a href="/admin">&lt;script&gt;Admin&lt;/script&gt;</a>', $html);
        $this->assertStringContainsString('Contatti', $html);
        $this->assertStringNotContainsString('<a href="#">Contatti</a>', $html);
        $this->assertStringNotContainsString('<script>Admin</script>', $html);
        $this->assertStringContainsString('href="/organi"', $html);
    }

    public function test_missing_footer_section_title_fails_during_construction(): void
    {
        $this->expectException(\ArgumentCountError::class);

        new FooterSection;
    }

    public function test_empty_footer_section_title_is_rejected_during_construction(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Field 'title'");

        new FooterSection(title: '');
    }

    public function test_footer_section_url_is_validated(): void
    {
        $this->expectException(ViewException::class);
        $this->expectExceptionMessage("Field 'url'");

        $this->blade('<x-italia::footer title="Comune"><x-slot:sections><x-italia::footer-section title="Servizi" url="bad url" /></x-slot:sections></x-italia::footer>');
    }

    public function test_footer_legal_link_supports_camel_case_data_element(): void
    {
        $html = (string) $this->blade('<x-italia::footer title="Comune"><x-italia::footer-legal-link url="/privacy" text="Privacy" dataElement="privacy-camel" /></x-italia::footer>');

        $this->assertStringContainsString('data-element="privacy-camel"', $html);
    }

    public function test_footer_legal_link_supports_kebab_case_data_element(): void
    {
        $html = (string) $this->blade('<x-italia::footer title="Comune"><x-italia::footer-legal-link url="/privacy" text="Privacy" data-element="privacy-kebab" /></x-italia::footer>');

        $this->assertStringContainsString('data-element="privacy-kebab"', $html);
    }

    public function test_footer_legal_link_supports_snake_case_data_element(): void
    {
        $html = (string) $this->blade('<x-italia::footer title="Comune"><x-italia::footer-legal-link url="/privacy" text="Privacy" data_element="privacy-snake" /></x-italia::footer>');

        $this->assertStringContainsString('data-element="privacy-snake"', $html);
    }

    public function test_footer_legal_link_requires_text_and_escapes_it(): void
    {
        try {
            $this->blade('<x-italia::footer title="Comune"><x-italia::footer-legal-link url="/privacy" text="" /></x-italia::footer>');
            $this->fail('A legal link without text must be rejected.');
        } catch (ViewException $exception) {
            $this->assertStringContainsString("Field 'text'", $exception->getMessage());
        }

        $html = (string) $this->blade('<x-italia::footer title="Comune"><x-italia::footer-legal-link url="/privacy" text="<script>Privacy</script>" /></x-italia::footer>');

        $this->assertStringContainsString('&lt;script&gt;Privacy&lt;/script&gt;', $html);
        $this->assertStringNotContainsString('<script>Privacy</script>', $html);
    }

    public function test_footer_social_link_renders_accessible_icon_and_requires_label(): void
    {
        $html = (string) $this->blade('<x-italia::footer title="Comune"><x-slot:social><x-italia::footer-social-link url="https://example.com" label="Community" icon="it-facebook" /></x-slot:social></x-italia::footer>');

        $this->assertStringContainsString('aria-label="Community"', $html);
        $this->assertStringContainsString('target="_blank" rel="noopener noreferrer"', $html);
        $this->assertStringContainsString('sprites.svg#it-facebook', $html);

        try {
            new FooterSocialLink(url: 'https://example.com');
            $this->fail('A social link without a label must be rejected during construction.');
        } catch (\ArgumentCountError $exception) {
            $this->assertStringContainsString('Too few arguments', $exception->getMessage());
        }
    }

    public function test_footer_copyright_defaults_to_current_year_and_is_suppressed_when_empty(): void
    {
        $html = (string) $this->blade('<x-italia::footer title="Comune" />');
        $empty = (string) $this->blade('<x-italia::footer title="Comune" copyright="" />');

        $this->assertStringContainsString('© ' . date('Y') . ' Comune', $html);
        $this->assertStringNotContainsString('it-footer-small-prints', $empty);
    }

    public function test_footer_places_copyright_in_small_prints_with_legal_links(): void
    {
        $html = (string) $this->blade('<x-italia::footer title="Comune" copyright="© Comune"><x-italia::footer-legal-link url="/privacy" text="Privacy" /></x-italia::footer>');
        $smallPrints = substr($html, (int) strpos($html, 'it-footer-small-prints'));

        $this->assertStringContainsString('href="/privacy"', $smallPrints);
        $this->assertStringContainsString('© Comune', $smallPrints);
        $this->assertStringNotContainsString('© Comune', substr($html, 0, (int) strpos($html, 'it-footer-small-prints')));
    }

    public function test_footer_logo_alt_defaults_to_title_and_brand_url_defaults_to_hash(): void
    {
        $html = (string) $this->blade('<x-italia::footer title="Comune" logo="/crest.svg" />');

        $this->assertStringContainsString('src="/crest.svg" alt="Comune"', $html);
        $this->assertStringContainsString('<a href="#">', $html);
    }

    public function test_footer_accepts_custom_id_and_data_attributes(): void
    {
        $html = (string) $this->blade('<x-italia::footer title="Comune" id="site-footer" data-element="footer" />');

        $this->assertStringContainsString('id="site-footer"', $html);
        $this->assertStringContainsString('data-element="footer"', $html);
    }

    public function test_footer_social_link_requires_valid_url(): void
    {
        $this->expectException(ViewException::class);
        $this->expectExceptionMessage("Field 'url'");

        $this->blade('<x-italia::footer title="Comune"><x-slot:social><x-italia::footer-social-link url="javascript:alert(1)" label="Social" /></x-slot:social></x-italia::footer>');
    }

    public function test_footer_social_link_requires_icon_when_explicitly_empty(): void
    {
        $this->expectException(ViewException::class);
        $this->expectExceptionMessage("Field 'icon'");

        $this->blade('<x-italia::footer title="Comune"><x-slot:social><x-italia::footer-social-link url="https://example.com" label="Social" icon="" /></x-slot:social></x-italia::footer>');
    }

    public function test_footer_section_slots_render_without_array_configuration(): void
    {
        $html = (string) $this->blade('<x-italia::footer title="Comune"><x-slot:sections><x-italia::footer-section title="Amministrazione" url="/amministrazione"><li>Uffici</li></x-italia::footer-section></x-slot:sections></x-italia::footer>');

        $this->assertStringContainsString('href="/amministrazione"', $html);
        $this->assertStringContainsString('<li>Uffici</li>', $html);
    }

    public function test_named_legal_links_slot_takes_precedence_over_default_slot(): void
    {
        $html = (string) $this->blade(<<<'BLADE'
<x-italia::footer title="Comune">
    <x-slot:legal-links><x-italia::footer-legal-link url="/privacy" text="Named privacy" /></x-slot:legal-links>
    <x-italia::footer-legal-link url="/other" text="Default link" />
</x-italia::footer>
BLADE);

        $this->assertStringContainsString('Named privacy', $html);
        $this->assertStringNotContainsString('Default link', $html);
    }

    public function test_footer_without_logo_uses_fallback_icon(): void
    {
        $html = (string) $this->blade('<x-italia::footer title="Comune" />');

        $this->assertStringContainsString('sprites.svg#it-code-circle', $html);
        $this->assertStringNotContainsString('<img', $html);
    }

    public function test_footer_empty_logo_uses_fallback_icon(): void
    {
        $html = (string) $this->blade('<x-italia::footer title="Comune" logo="" />');

        $this->assertStringContainsString('sprites.svg#it-code-circle', $html);
        $this->assertStringNotContainsString('<img', $html);
    }

    public function test_footer_data_image_logo_renders_as_image(): void
    {
        $html = (string) $this->blade('<x-italia::footer title="Comune" logo="data:image/svg+xml;base64,PHN2Zy8+" />');

        $this->assertStringContainsString('src="data:image/svg+xml;base64,PHN2Zy8+"', $html);
        $this->assertStringContainsString('<img class="icon"', $html);
    }

    public function test_standalone_footer_does_not_infer_title_from_application_name(): void
    {
        config(['app.name' => 'Laravel']);

        $html = (string) $this->blade('<x-italia::footer />');

        $this->assertStringNotContainsString('it-brand-text', $html);
    }

    public function test_footer_renders_with_an_explicit_title(): void
    {
        $html = (string) $this->blade('<x-italia::footer title="Comune" />');

        $this->assertStringContainsString('<h2 class="no_toc">Comune</h2>', $html);
    }

    public function test_footer_renders_logo_and_copyright_without_title(): void
    {
        config(['app.name' => '']);

        $html = (string) $this->blade('<x-italia::footer logo="/logo.svg" copyright="© Comune" />');

        $this->assertStringContainsString('src="/logo.svg" alt=""', $html);
        $this->assertStringContainsString('© Comune', $html);
        $this->assertStringNotContainsString('it-brand-text', $html);
    }

    public function test_footer_renders_sections_and_legal_links_without_title(): void
    {
        config(['app.name' => '']);

        $html = (string) $this->blade(<<<'BLADE'
<x-italia::footer>
    <x-slot:sections><div>Services</div></x-slot:sections>
    <x-slot:legal-links><x-italia::footer-legal-link url="/privacy" text="Privacy" /></x-slot:legal-links>
</x-italia::footer>
BLADE);

        $this->assertStringContainsString('Services', $html);
        $this->assertStringContainsString('href="/privacy"', $html);
        $this->assertStringNotContainsString('it-brand-text', $html);
    }
}
