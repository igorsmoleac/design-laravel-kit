<?php

namespace IgorSmoleac\DesignLaravelKit\Tests\Unit\Components;

use IgorSmoleac\DesignLaravelKit\DesignLaravelKitServiceProvider;
use Illuminate\View\ViewException;
use Orchestra\Testbench\TestCase;

class HeaderCenterTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [DesignLaravelKitServiceProvider::class];
    }

    public function test_renders_title_and_tagline_from_named_attributes(): void
    {
        $html = (string) $this->blade('<x-italia::header-center title="Comune di Roma" tagline="Portale istituzionale" />');

        $this->assertStringContainsString('it-header-center-wrapper', $html);
        $this->assertStringContainsString('Comune di Roma', $html);
        $this->assertStringContainsString('Portale istituzionale', $html);
    }

    public function test_renders_logo_and_brand_url(): void
    {
        $html = (string) $this->blade('<x-italia::header-center title="Comune" logo="/img/logo.svg" logo-alt="Stemma" url="/home" />');

        $this->assertStringContainsString('<a href="/home">', $html);
        $this->assertStringContainsString('src="/img/logo.svg" alt="Stemma"', $html);
    }

    public function test_social_slot_renders_helper_links(): void
    {
        $html = (string) $this->blade(<<<'BLADE'
<x-italia::header-center title="Comune">
    <x-slot:social-links>
        <x-italia::header-social-link url="https://facebook.com" icon="it-facebook" label="Facebook" />
    </x-slot:social-links>
</x-italia::header-center>
BLADE);

        $this->assertStringContainsString('it-socials', $html);
        $this->assertStringContainsString('aria-label="Facebook"', $html);
        $this->assertStringContainsString('sprites.svg#it-facebook', $html);
        $this->assertStringContainsString('rel="noopener noreferrer"', $html);
    }

    public function test_search_link_and_display_options_are_rendered(): void
    {
        $html = (string) $this->blade('<x-italia::header-center title="Comune" search-url="/search" light small />');

        $this->assertStringContainsString('it-header-center-wrapper it-small-header theme-light', $html);
        $this->assertStringContainsString('href="/search"', $html);
        $this->assertStringContainsString('aria-label="Cerca"', $html);
    }

    public function test_missing_required_title_is_rejected(): void
    {
        $this->expectException(ViewException::class);
        $this->expectExceptionMessage("Field 'title'");

        $this->blade('<x-italia::header-center />');
    }

    public function test_invalid_search_url_is_rejected(): void
    {
        $this->expectException(ViewException::class);
        $this->expectExceptionMessage("Field 'searchUrl'");

        $this->blade('<x-italia::header-center title="Comune" search-url="bad url" />');
    }

    public function test_old_social_array_attribute_is_rejected(): void
    {
        $this->expectException(ViewException::class);
        $this->expectExceptionMessage("The 'social-links' array attribute is no longer supported");

        $this->blade('<x-italia::header-center title="Comune" :social-links="[]" />');
    }

    public function test_fallback_icon_and_brand_url_default_are_preserved(): void
    {
        $html = (string) $this->blade('<x-italia::header-center title="Comune" />');

        $this->assertStringContainsString('href="#"', $html);
        $this->assertStringContainsString('sprites.svg#it-code-circle', $html);
        $this->assertStringNotContainsString('it-right-zone', $html);
    }

    public function test_custom_id_and_data_attributes_are_forwarded(): void
    {
        $html = (string) $this->blade('<x-italia::header-center title="Comune" id="center" data-element="center" />');

        $this->assertStringContainsString('id="center"', $html);
        $this->assertStringContainsString('data-element="center"', $html);
    }

    public function test_tagline_is_omitted_when_null_and_brand_url_defaults_to_hash(): void
    {
        $html = (string) $this->blade('<x-italia::header-center title="Comune" />');

        $this->assertStringContainsString('<a href="#">', $html);
        $this->assertStringNotContainsString('it-brand-tagline', $html);
    }

    public function test_title_and_tagline_are_escaped(): void
    {
        $html = (string) $this->blade('<x-italia::header-center title="<script>Comune</script>" tagline="<b>Portale</b>" />');

        $this->assertStringContainsString('&lt;script&gt;Comune&lt;/script&gt;', $html);
        $this->assertStringContainsString('&lt;b&gt;Portale&lt;/b&gt;', $html);
        $this->assertStringNotContainsString('<script>Comune</script>', $html);
        $this->assertStringNotContainsString('<b>Portale</b>', $html);
    }

    public function test_social_slot_must_have_required_label_and_renders_without_search(): void
    {
        $html = (string) $this->blade('<x-italia::header-center title="Comune"><x-slot:social-links><x-italia::header-social-link url="https://example.com" label="Social" /></x-slot:social-links></x-italia::header-center>');

        $this->assertStringContainsString('it-right-zone', $html);
        $this->assertStringContainsString('aria-label="Social"', $html);
        $this->assertStringNotContainsString('it-search-wrapper', $html);

        try {
            $this->blade('<x-italia::header-center title="Comune"><x-slot:social-links><x-italia::header-social-link url="https://example.com" /></x-slot:social-links></x-italia::header-center>');
            $this->fail('A header social link without a label must be rejected.');
        } catch (ViewException $exception) {
            $this->assertStringContainsString("Field 'label'", $exception->getMessage());
        }
    }

    public function test_logo_alt_defaults_to_title_and_custom_alt_is_escaped(): void
    {
        $defaultAlt = (string) $this->blade('<x-italia::header-center title="Comune" logo="/crest.svg" />');
        $customAlt = (string) $this->blade('<x-italia::header-center title="Comune" logo="/crest.svg" logo-alt="<script>Stemma</script>" />');

        $this->assertStringContainsString('src="/crest.svg" alt="Comune"', $defaultAlt);
        $this->assertStringContainsString('alt="&lt;script&gt;Stemma&lt;/script&gt;"', $customAlt);
        $this->assertStringNotContainsString('alt="<script>Stemma</script>"', $customAlt);
    }

    public function test_search_and_social_slots_are_independent(): void
    {
        $searchOnly = (string) $this->blade('<x-italia::header-center title="Comune" search-url="/cerca" />');
        $emptySocial = (string) $this->blade('<x-italia::header-center title="Comune"><x-slot:social-links></x-slot:social-links></x-italia::header-center>');

        $this->assertStringContainsString('it-search-wrapper', $searchOnly);
        $this->assertStringNotContainsString('it-socials', $searchOnly);
        $this->assertStringNotContainsString('it-right-zone', $emptySocial);
    }

    public function test_social_link_url_must_be_valid(): void
    {
        $this->expectException(ViewException::class);
        $this->expectExceptionMessage("Field 'url'");

        $this->blade('<x-italia::header-center title="Comune"><x-slot:social-links><x-italia::header-social-link url="bad url" label="Social" /></x-slot:social-links></x-italia::header-center>');
    }

    public function test_header_social_link_requires_icon_when_explicitly_empty(): void
    {
        $this->expectException(ViewException::class);
        $this->expectExceptionMessage("Field 'icon'");

        $this->blade('<x-italia::header-center title="Comune"><x-slot:social-links><x-italia::header-social-link url="https://example.com" label="Social" icon="" /></x-slot:social-links></x-italia::header-center>');
    }

    public function test_social_slot_accepts_multiple_links_in_render_order(): void
    {
        $html = (string) $this->blade('<x-italia::header-center title="Comune"><x-slot:social-links><x-italia::header-social-link url="https://facebook.com" label="Facebook" /><x-italia::header-social-link url="https://mastodon.social" label="Mastodon" /></x-slot:social-links></x-italia::header-center>');

        $this->assertLessThan(strpos($html, 'aria-label="Mastodon"'), strpos($html, 'aria-label="Facebook"'));
    }

    public function test_empty_logo_uses_fallback_icon(): void
    {
        $html = (string) $this->blade('<x-italia::header-center title="Comune" logo="" />');

        $this->assertStringContainsString('sprites.svg#it-code-circle', $html);
        $this->assertStringNotContainsString('<img', $html);
    }

    public function test_data_image_logo_renders_as_image(): void
    {
        $html = (string) $this->blade('<x-italia::header-center title="Comune" logo="data:image/svg+xml;base64,PHN2Zy8+" />');

        $this->assertStringContainsString('src="data:image/svg+xml;base64,PHN2Zy8+"', $html);
        $this->assertStringContainsString('<img class="icon"', $html);
    }
}
