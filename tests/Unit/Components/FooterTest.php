<?php

namespace IgorSmoleac\DesignLaravelKit\Tests\Unit\Components;

use IgorSmoleac\DesignLaravelKit\Components\Footer;
use IgorSmoleac\DesignLaravelKit\DesignLaravelKitServiceProvider;
use Orchestra\Testbench\TestCase;

class FooterTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [DesignLaravelKitServiceProvider::class];
    }

    private function sections(): array
    {
        return [
            [
                'title' => 'Amministrazione',
                'links' => [
                    ['url' => '/organi', 'text' => 'Organi di governo'],
                    ['url' => '/uffici', 'text' => 'Uffici'],
                ],
            ],
            [
                'title' => 'Novità',
                'links' => [
                    ['url' => '/notizie', 'text' => 'Notizie'],
                ],
            ],
        ];
    }

    private function contacts(): array
    {
        return [
            ['type' => 'address', 'value' => 'Piazza del Campidoglio, 1 — 00186 Roma'],
            ['type' => 'phone', 'value' => '+39 06 0606', 'label' => 'Telefono'],
            ['type' => 'email', 'value' => 'protocollo@comune.roma.it'],
            ['type' => 'pec', 'value' => 'protocollo@pec.comune.roma.it', 'label' => 'PEC'],
        ];
    }

    private function socialLinks(): array
    {
        return [
            ['url' => 'https://facebook.com', 'icon' => 'it-facebook', 'label' => 'Facebook'],
            ['url' => 'https://twitter.com', 'icon' => 'it-twitter', 'label' => 'Twitter'],
        ];
    }

    private function legalLinks(): array
    {
        return [
            ['url' => '/privacy', 'text' => 'Privacy policy', 'dataElement' => 'privacy-policy-link'],
            ['url' => '/note-legali', 'text' => 'Note legali'],
            ['url' => '/accessibilita', 'text' => 'Dichiarazione di accessibilità', 'dataElement' => 'accessibility-link'],
            ['url' => '/segnala', 'text' => 'Segnala un problema'],
        ];
    }

    public function test_renders_it_footer(): void
    {
        $html = (string) $this->blade('<x-italia::footer title="Comune di Roma" />');

        $this->assertStringContainsString('<footer class="it-footer">', $html);
        $this->assertStringContainsString('it-footer-main', $html);
    }

    public function test_title_renders_inside_it_brand_text(): void
    {
        $html = (string) $this->blade('<x-italia::footer title="Comune di Roma" />');

        $this->assertMatchesRegularExpression('/it-brand-text[^>]*>\s*<h2 class="no_toc">Comune di Roma<\/h2>/s', $html);
    }

    public function test_subtitle_renders_when_provided(): void
    {
        $html = (string) $this->blade('<x-italia::footer title="Comune di Roma" subtitle="Portale istituzionale" />');

        $this->assertStringContainsString('<h3 class="no_toc d-none d-md-block">Portale istituzionale</h3>', $html);
    }

    public function test_subtitle_is_omitted_when_null(): void
    {
        $html = (string) $this->blade('<x-italia::footer title="Comune di Roma" />');

        $this->assertStringNotContainsString('<h3', $html);
    }

    public function test_logo_renders_as_img_with_alt(): void
    {
        $html = (string) $this->blade('<x-italia::footer title="Comune di Roma" logo="https://example.com/logo.svg" logo-alt="Stemma del Comune" />');

        $this->assertStringContainsString('<img class="icon" src="https://example.com/logo.svg" alt="Stemma del Comune">', $html);
    }

    public function test_logo_alt_defaults_to_title(): void
    {
        $html = (string) $this->blade('<x-italia::footer title="Comune di Roma" logo="https://example.com/logo.svg" />');

        $this->assertStringContainsString('alt="Comune di Roma"', $html);
    }

    public function test_fallback_icon_without_logo(): void
    {
        $html = (string) $this->blade('<x-italia::footer title="Comune di Roma" />');

        $this->assertStringContainsString('sprites.svg#it-code-circle', $html);
        $this->assertStringNotContainsString('<img', $html);
    }

    public function test_brand_url_defaults_to_hash(): void
    {
        $html = (string) $this->blade('<x-italia::footer title="Comune di Roma" />');

        $this->assertStringContainsString('<a href="#">', $html);
    }

    public function test_brand_url_uses_provided_url(): void
    {
        $html = (string) $this->blade('<x-italia::footer title="Comune di Roma" url="/" />');

        $this->assertStringContainsString('<a href="/">', $html);
    }

    public function test_sections_render_in_columns(): void
    {
        $html = (string) $this->blade('<x-italia::footer title="Comune di Roma" :sections="$sections" />', ['sections' => $this->sections()]);

        $this->assertStringContainsString('col-lg-3 col-md-3 col-sm-6', $html);
        $this->assertSame(2, substr_count($html, 'col-lg-3 col-md-3 col-sm-6'));
        $this->assertStringContainsString('Organi di governo', $html);
        $this->assertStringContainsString('href="/notizie"', $html);
        $this->assertStringContainsString('footer-list link-list clearfix', $html);
    }

    public function test_section_title_renders_as_link_when_url_provided(): void
    {
        $html = (string) $this->blade('<x-italia::footer title="Comune di Roma" :sections="$sections" />', [
            'sections' => [['title' => 'Amministrazione', 'url' => '/amministrazione', 'links' => [['url' => '/organi', 'text' => 'Organi']]]],
        ]);

        $this->assertStringContainsString('<h4>', $html);
        $this->assertStringContainsString('<a href="/amministrazione">Amministrazione</a>', $html);
    }

    public function test_contacts_render_with_link_list(): void
    {
        $html = (string) $this->blade('<x-italia::footer title="Comune di Roma" :contacts="$contacts" />', ['contacts' => $this->contacts()]);

        $this->assertStringContainsString('footer-list link-list clearfix', $html);
        $this->assertStringContainsString('Piazza del Campidoglio, 1 — 00186 Roma', $html);
        $this->assertStringContainsString('href="tel:+39060606"', $html);
        $this->assertStringContainsString('href="mailto:protocollo@comune.roma.it"', $html);
        $this->assertStringContainsString('href="mailto:protocollo@pec.comune.roma.it"', $html);
        $this->assertStringContainsString('Telefono: +39 06 0606', $html);
        $this->assertStringContainsString('sprites.svg#it-telephone', $html);
        $this->assertStringContainsString('sprites.svg#it-mail', $html);
    }

    public function test_address_renders_as_paragraph_not_link(): void
    {
        $html = (string) $this->blade('<x-italia::footer title="Comune di Roma" :contacts="$contacts" />', ['contacts' => $this->contacts()]);

        $this->assertMatchesRegularExpression('/<p>\s*Piazza del Campidoglio/s', $html);
        $this->assertStringNotContainsString('href="Piazza', $html);
    }

    public function test_social_links_render_with_aria_label_and_target_blank(): void
    {
        $html = (string) $this->blade('<x-italia::footer title="Comune di Roma" :social-links="$social" />', ['social' => $this->socialLinks()]);

        $this->assertStringContainsString('aria-label="Facebook"', $html);
        $this->assertStringContainsString('aria-label="Twitter"', $html);
        $this->assertStringContainsString('target="_blank"', $html);
        $this->assertStringContainsString('rel="noopener noreferrer"', $html);
        $this->assertStringContainsString('sprites.svg#it-facebook', $html);
        $this->assertStringContainsString('sprites.svg#it-twitter', $html);
    }

    public function test_legal_links_render_in_small_prints_list(): void
    {
        $html = (string) $this->blade('<x-italia::footer title="Comune di Roma" :legal-links="$legal" />', ['legal' => $this->legalLinks()]);

        $this->assertStringContainsString('it-footer-small-prints', $html);
        $this->assertStringContainsString('it-footer-small-prints-list', $html);
        $this->assertStringContainsString('href="/privacy"', $html);
        $this->assertStringContainsString('href="/note-legali"', $html);
        $this->assertStringContainsString('href="/segnala"', $html);
    }

    public function test_privacy_link_has_agid_data_element(): void
    {
        $html = (string) $this->blade('<x-italia::footer title="Comune di Roma" :legal-links="$legal" />', ['legal' => $this->legalLinks()]);

        $this->assertMatchesRegularExpression('/<a[^>]*href="\/privacy"[^>]*data-element="privacy-policy-link"[^>]*>/', $html);
    }

    public function test_accessibility_link_has_agid_data_element(): void
    {
        $html = (string) $this->blade('<x-italia::footer title="Comune di Roma" :legal-links="$legal" />', ['legal' => $this->legalLinks()]);

        $this->assertMatchesRegularExpression('/<a[^>]*href="\/accessibilita"[^>]*data-element="accessibility-link"[^>]*>/', $html);
    }

    public function test_legal_link_without_data_element_renders_no_attribute(): void
    {
        $html = (string) $this->blade('<x-italia::footer title="Comune di Roma" :legal-links="$legal" />', ['legal' => $this->legalLinks()]);

        $this->assertMatchesRegularExpression('/<a[^>]*href="\/note-legali"[^>]*>(?:(?!data-element).)*<\/a>/s', $html);
    }

    public function test_copyright_renders_in_small_prints_not_in_main(): void
    {
        $html = (string) $this->blade('<x-italia::footer title="Comune di Roma" copyright="© 2026 Comune di Roma — Tutti i diritti riservati" />');

        $main = substr($html, 0, (int) strpos($html, 'it-footer-small-prints'));
        $smallPrints = substr($html, (int) strpos($html, 'it-footer-small-prints'));

        $this->assertStringContainsString('it-footer-small-prints', $html);
        $this->assertStringContainsString('© 2026 Comune di Roma — Tutti i diritti riservati', $smallPrints);
        $this->assertStringNotContainsString('© 2026 Comune di Roma — Tutti i diritti riservati', $main);
    }

    public function test_copyright_renders_in_small_prints_without_legal_links(): void
    {
        $html = (string) $this->blade('<x-italia::footer title="Comune di Roma" />');

        $smallPrints = substr($html, (int) strpos($html, 'it-footer-small-prints'));

        $this->assertStringContainsString('it-footer-small-prints', $html);
        $this->assertStringContainsString('© ' . date('Y') . ' Comune di Roma', $smallPrints);
        $this->assertStringNotContainsString('it-footer-small-prints-list', $html);
    }

    public function test_empty_copyright_without_legal_links_renders_no_small_prints(): void
    {
        $html = (string) $this->blade('<x-italia::footer title="Comune di Roma" copyright="" />');

        $this->assertStringNotContainsString('it-footer-small-prints', $html);
    }

    public function test_copyright_text_generates_default(): void
    {
        $footer = new Footer(title: 'Comune di Roma');

        $this->assertSame('© ' . date('Y') . ' Comune di Roma', $footer->copyrightText());
    }

    public function test_current_year_returns_integer(): void
    {
        $footer = new Footer(title: 'Comune di Roma');

        $this->assertSame((int) date('Y'), $footer->currentYear());
        $this->assertIsInt($footer->currentYear());
    }

    public function test_light_renders_theme_light(): void
    {
        $html = (string) $this->blade('<x-italia::footer title="Comune di Roma" light />');

        $this->assertStringContainsString('<footer class="it-footer theme-light">', $html);
    }

    public function test_empty_arrays_do_not_break_render(): void
    {
        $html = (string) $this->blade('<x-italia::footer title="Comune di Roma" />');

        $this->assertStringContainsString('it-footer-main', $html);
        $this->assertStringNotContainsString('footer-list', $html);
        $this->assertStringNotContainsString('list-inline text-left social', $html);
    }

    public function test_full_footer_renders_all_parts(): void
    {
        $html = (string) $this->blade(
            '<x-italia::footer title="Comune di Roma" subtitle="Portale istituzionale" :sections="$sections" :contacts="$contacts" :social-links="$social" :legal-links="$legal" />',
            [
                'sections' => $this->sections(),
                'contacts' => $this->contacts(),
                'social' => $this->socialLinks(),
                'legal' => $this->legalLinks(),
            ]
        );

        $this->assertStringContainsString('it-brand-wrapper', $html);
        $this->assertStringContainsString('Portale istituzionale', $html);
        $this->assertStringContainsString('footer-list link-list clearfix', $html);
        $this->assertStringContainsString('list-inline text-left social', $html);
        $this->assertStringContainsString('it-footer-small-prints-list', $html);
        $this->assertStringContainsString('data-element="privacy-policy-link"', $html);
        $this->assertStringContainsString('data-element="accessibility-link"', $html);
    }
}
