<?php

namespace IgorSmoleac\DesignLaravelKit\Tests\Unit\Components;

use IgorSmoleac\DesignLaravelKit\DesignLaravelKitServiceProvider;
use Orchestra\Testbench\TestCase;

class HeaderSlimTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [DesignLaravelKitServiceProvider::class];
    }

    public function test_renders_wrapper_structure(): void
    {
        $html = (string) $this->blade('<x-italia::header-slim />');

        $this->assertStringContainsString('it-header-slim-wrapper', $html);
        $this->assertStringContainsString('it-header-slim-wrapper-content', $html);
        $this->assertStringContainsString('container-xxl', $html);
    }

    public function test_renders_default_ente_as_span_without_link(): void
    {
        $html = (string) $this->blade('<x-italia::header-slim />');

        $this->assertStringContainsString('Ente appartenenza', $html);
        $this->assertMatchesRegularExpression('/<span class="d-none d-lg-block navbar-brand">/', $html);
        $this->assertStringNotContainsString('navbar-brand" href=', $html);
    }

    public function test_ente_renders_as_link_when_ente_url_provided(): void
    {
        $html = (string) $this->blade('<x-italia::header-slim ente="Comune di Roma" ente-url="https://www.comune.roma.it" />');

        $this->assertMatchesRegularExpression('/<a class="d-none d-lg-block navbar-brand" href="https:\/\/www\.comune\.roma\.it">Comune di Roma<\/a>/', $html);
    }

    public function test_light_theme_renders_theme_light_class(): void
    {
        $html = (string) $this->blade('<x-italia::header-slim light />');

        $this->assertStringContainsString('it-header-slim-wrapper theme-light', $html);
    }

    public function test_no_theme_light_class_by_default(): void
    {
        $html = (string) $this->blade('<x-italia::header-slim />');

        $this->assertStringNotContainsString('theme-light', $html);
    }

    public function test_sticky_renders_data_bs_toggle(): void
    {
        $html = (string) $this->blade('<x-italia::header-slim sticky />');

        $this->assertStringContainsString('data-bs-toggle="sticky"', $html);
    }

    public function test_no_sticky_attribute_by_default(): void
    {
        $html = (string) $this->blade('<x-italia::header-slim />');

        $this->assertStringNotContainsString('data-bs-toggle="sticky"', $html);
    }

    public function test_links_render_in_nav_mobile(): void
    {
        $html = (string) $this->blade('<x-italia::header-slim :links="[[\'url\' => \'/\', \'text\' => \'Pagina iniziale\'], [\'url\' => \'/contatti\', \'text\' => \'Contatti\']]" />');

        $this->assertStringContainsString('nav-mobile', $html);
        $this->assertStringContainsString('link-list-wrapper collapse', $html);
        $this->assertStringContainsString('href="/"', $html);
        $this->assertStringContainsString('>Pagina iniziale</a>', $html);
        $this->assertStringContainsString('href="/contatti"', $html);
        $this->assertStringContainsString('>Contatti</a>', $html);
    }

    public function test_active_link_has_aria_current_and_active_class(): void
    {
        $html = (string) $this->blade('<x-italia::header-slim :links="[[\'url\' => \'/\', \'text\' => \'Pagina iniziale\'], [\'url\' => \'/contatti\', \'text\' => \'Contatti\', \'active\' => true]]" />');

        $this->assertStringContainsString('dropdown-item list-item active', $html);
        $this->assertStringContainsString('aria-current="page"', $html);
    }

    public function test_languages_render_dropdown(): void
    {
        $html = (string) $this->blade('<x-italia::header-slim :languages="[[\'code\' => \'it\', \'label\' => \'ITA\', \'active\' => true], [\'code\' => \'en\', \'label\' => \'ENG\']]" />');

        $this->assertStringContainsString('nav-link dropdown-toggle', $html);
        $this->assertStringContainsString('data-bs-toggle="dropdown"', $html);
        $this->assertStringContainsString('aria-expanded="false"', $html);
        $this->assertStringContainsString('ITA', $html);
        $this->assertStringContainsString('ENG', $html);
    }

    public function test_active_language_has_visually_hidden_selezionata(): void
    {
        $html = (string) $this->blade('<x-italia::header-slim :languages="[[\'code\' => \'it\', \'label\' => \'ITA\', \'active\' => true], [\'code\' => \'en\', \'label\' => \'ENG\']]" />');

        $this->assertStringContainsString('ITA <span class="visually-hidden">selezionata</span>', $html);
        $this->assertStringNotContainsString('ENG <span class="visually-hidden"', $html);
    }

    public function test_dropdown_toggle_shows_active_language_label(): void
    {
        $html = (string) $this->blade('<x-italia::header-slim :languages="[[\'code\' => \'it\', \'label\' => \'ITA\'], [\'code\' => \'en\', \'label\' => \'ENG\', \'active\' => true]]" />');

        $this->assertMatchesRegularExpression('/aria-expanded="false">\s*<span class="visually-hidden">[^<]*<\/span>\s*<span>ENG<\/span>/', $html);
    }

    public function test_login_button_renders(): void
    {
        $html = (string) $this->blade('<x-italia::header-slim login-url="/login" />');

        $this->assertStringContainsString('it-access-top-wrapper', $html);
        $this->assertStringContainsString('btn btn-sm btn-primary', $html);
        $this->assertStringContainsString('href="/login"', $html);
        $this->assertMatchesRegularExpression('/>\s*Accedi\s*<\/a>/', $html);
    }

    public function test_login_label_is_customizable(): void
    {
        $html = (string) $this->blade('<x-italia::header-slim login-url="/login" login-label="Entra" />');

        $this->assertMatchesRegularExpression('/>\s*Entra\s*<\/a>/', $html);
    }

    public function test_login_hidden_without_login_url(): void
    {
        $html = (string) $this->blade('<x-italia::header-slim />');

        $this->assertStringNotContainsString('it-access-top-wrapper', $html);
        $this->assertStringNotContainsString('Accedi', $html);
    }

    public function test_aria_controls_references_existing_id(): void
    {
        $html = (string) $this->blade('<x-italia::header-slim :links="[[\'url\' => \'/\', \'text\' => \'Link 1\']]" />');

        preg_match('/aria-controls="([^"]+)"/', $html, $controls);
        preg_match('/href="#([^"]+)"[^>]*role="button"/', $html, $hrefTarget);

        $this->assertNotEmpty($controls);
        $this->assertMatchesRegularExpression('/id="' . $controls[1] . '"/', $html);
        $this->assertSame($controls[1], $hrefTarget[1]);
    }

    public function test_menu_id_is_unique_per_instance(): void
    {
        $first = (string) $this->blade('<x-italia::header-slim :links="[[\'url\' => \'/\', \'text\' => \'Link 1\']]" />');
        $second = (string) $this->blade('<x-italia::header-slim :links="[[\'url\' => \'/\', \'text\' => \'Link 1\']]" />');

        preg_match('/id="(dlk-header-slim-\d+-mobile-menu)"/', $first, $firstId);
        preg_match('/id="(dlk-header-slim-\d+-mobile-menu)"/', $second, $secondId);

        $this->assertNotEmpty($firstId);
        $this->assertNotEmpty($secondId);
        $this->assertNotSame($firstId[1], $secondId[1]);
    }

    public function test_empty_links_and_languages_not_rendered(): void
    {
        $html = (string) $this->blade('<x-italia::header-slim />');

        $this->assertStringNotContainsString('nav-mobile', $html);
        $this->assertStringNotContainsString('it-opener', $html);
        $this->assertStringNotContainsString('dropdown-menu', $html);
        $this->assertStringNotContainsString('it-header-slim-right-zone', $html);
    }

    public function test_right_zone_renders_with_login_only(): void
    {
        $html = (string) $this->blade('<x-italia::header-slim login-url="/login" />');

        $this->assertStringContainsString('it-header-slim-right-zone', $html);
        $this->assertStringNotContainsString('dropdown-menu', $html);
    }

    public function test_mobile_opener_has_expand_icon(): void
    {
        $html = (string) $this->blade('<x-italia::header-slim :links="[[\'url\' => \'/\', \'text\' => \'Link 1\']]" />');

        $this->assertStringContainsString('it-opener d-lg-none', $html);
        $this->assertStringContainsString('sprites.svg#it-expand', $html);
    }

    public function test_custom_id_propagates_to_menu_id(): void
    {
        $html = (string) $this->blade('<x-italia::header-slim id="my-header" :links="[[\'url\' => \'/\', \'text\' => \'Link 1\']]" />');

        $this->assertStringContainsString('id="my-header-mobile-menu"', $html);
        $this->assertStringContainsString('aria-controls="my-header-mobile-menu"', $html);
    }
}
