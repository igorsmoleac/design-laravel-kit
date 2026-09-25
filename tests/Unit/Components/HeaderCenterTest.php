<?php

namespace IgorSmoleac\DesignLaravelKit\Tests\Unit\Components;

use IgorSmoleac\DesignLaravelKit\DesignLaravelKitServiceProvider;
use Orchestra\Testbench\TestCase;

class HeaderCenterTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [DesignLaravelKitServiceProvider::class];
    }

    public function test_renders_wrapper_structure(): void
    {
        $html = (string) $this->blade('<x-italia::header-center title="Comune di Roma" />');

        $this->assertStringContainsString('it-header-center-wrapper', $html);
        $this->assertStringContainsString('it-header-center-content-wrapper', $html);
        $this->assertStringContainsString('container-xxl', $html);
    }

    public function test_title_renders_in_brand_title(): void
    {
        $html = (string) $this->blade('<x-italia::header-center title="Comune di Roma" />');

        $this->assertMatchesRegularExpression('/<div class="it-brand-title">\s*Comune di Roma\s*<\/div>/', $html);
    }

    public function test_tagline_renders_when_provided(): void
    {
        $html = (string) $this->blade('<x-italia::header-center title="Comune di Roma" tagline="Portale istituzionale" />');

        $this->assertMatchesRegularExpression('/<div class="it-brand-tagline d-none d-md-block">\s*Portale istituzionale\s*<\/div>/', $html);
    }

    public function test_tagline_not_rendered_when_null(): void
    {
        $html = (string) $this->blade('<x-italia::header-center title="Comune di Roma" />');

        $this->assertStringNotContainsString('it-brand-tagline', $html);
    }

    public function test_logo_renders_as_img_with_alt(): void
    {
        $html = (string) $this->blade('<x-italia::header-center title="Comune di Roma" logo="/img/logo.png" logo-alt="Stemma del Comune" />');

        $this->assertStringContainsString('<img class="icon" src="/img/logo.png" alt="Stemma del Comune">', $html);
        $this->assertStringNotContainsString('sprites.svg#it-code-circle', $html);
    }

    public function test_logo_alt_defaults_to_title(): void
    {
        $html = (string) $this->blade('<x-italia::header-center title="Comune di Roma" logo="/img/logo.png" />');

        $this->assertStringContainsString('alt="Comune di Roma"', $html);
    }

    public function test_without_logo_renders_fallback_icon(): void
    {
        $html = (string) $this->blade('<x-italia::header-center title="Comune di Roma" />');

        $this->assertStringContainsString('sprites.svg#it-code-circle', $html);
        $this->assertStringContainsString('icon-xl', $html);
        $this->assertStringNotContainsString('<img', $html);
    }

    public function test_url_renders_on_brand_link(): void
    {
        $html = (string) $this->blade('<x-italia::header-center title="Comune di Roma" url="/homepage" />');

        $this->assertMatchesRegularExpression('/<a href="\/homepage">\s*<img|<a href="\/homepage">\s*<svg/', $html);
    }

    public function test_brand_url_defaults_to_hash(): void
    {
        $html = (string) $this->blade('<x-italia::header-center title="Comune di Roma" />');

        $this->assertStringContainsString('<a href="#">', $html);
    }

    public function test_small_renders_it_small_header(): void
    {
        $html = (string) $this->blade('<x-italia::header-center title="Comune di Roma" small />');

        $this->assertStringContainsString('it-header-center-wrapper it-small-header', $html);
    }

    public function test_light_renders_theme_light(): void
    {
        $html = (string) $this->blade('<x-italia::header-center title="Comune di Roma" light />');

        $this->assertStringContainsString('theme-light', $html);
    }

    public function test_no_small_or_light_by_default(): void
    {
        $html = (string) $this->blade('<x-italia::header-center title="Comune di Roma" />');

        $this->assertStringNotContainsString('it-small-header', $html);
        $this->assertStringNotContainsString('theme-light', $html);
    }

    public function test_social_links_render_with_aria_label_and_target(): void
    {
        $html = (string) $this->blade('<x-italia::header-center title="Comune di Roma" :social-links="[[\'url\' => \'https://facebook.com\', \'icon\' => \'it-facebook\', \'label\' => \'Facebook\'], [\'url\' => \'https://twitter.com\', \'icon\' => \'it-twitter\', \'label\' => \'Twitter\']]" />');

        $this->assertStringContainsString('it-socials', $html);
        $this->assertStringContainsString('aria-label="Facebook"', $html);
        $this->assertStringContainsString('aria-label="Twitter"', $html);
        $this->assertStringContainsString('target="_blank"', $html);
        $this->assertStringContainsString('rel="noopener noreferrer"', $html);
        $this->assertStringContainsString('sprites.svg#it-facebook', $html);
        $this->assertStringContainsString('sprites.svg#it-twitter', $html);
        $this->assertStringContainsString('Seguici su', $html);
    }

    public function test_search_renders(): void
    {
        $html = (string) $this->blade('<x-italia::header-center title="Comune di Roma" search-url="/search" />');

        $this->assertStringContainsString('it-search-wrapper', $html);
        $this->assertStringContainsString('search-link rounded-icon', $html);
        $this->assertStringContainsString('href="/search"', $html);
        $this->assertStringContainsString('sprites.svg#it-search', $html);
        $this->assertStringContainsString('aria-label="Cerca"', $html);
        $this->assertStringContainsString('Cerca', $html);
    }

    public function test_right_zone_absent_without_social_and_search(): void
    {
        $html = (string) $this->blade('<x-italia::header-center title="Comune di Roma" />');

        $this->assertStringNotContainsString('it-right-zone', $html);
        $this->assertStringNotContainsString('it-socials', $html);
        $this->assertStringNotContainsString('it-search-wrapper', $html);
    }

    public function test_ids_are_unique_per_instance(): void
    {
        $first = (string) $this->blade('<x-italia::header-center title="Comune di Roma" />');
        $second = (string) $this->blade('<x-italia::header-center title="Comune di Roma" />');

        preg_match('/id="(dlk-header-center-\d+)"/', $first, $firstId);
        preg_match('/id="(dlk-header-center-\d+)"/', $second, $secondId);

        $this->assertNotEmpty($firstId);
        $this->assertNotEmpty($secondId);
        $this->assertNotSame($firstId[1], $secondId[1]);
    }
}
