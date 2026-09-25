<?php

namespace IgorSmoleac\DesignLaravelKit\Tests\Unit\Components;

use IgorSmoleac\DesignLaravelKit\DesignLaravelKitServiceProvider;
use Orchestra\Testbench\TestCase;

class HeaderNavbarTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [DesignLaravelKitServiceProvider::class];
    }

    public function test_renders_wrapper_structure(): void
    {
        $html = (string) $this->blade('<x-italia::header-navbar :items="[[\'text\' => \'Home\', \'url\' => \'/\']]" />');

        $this->assertStringContainsString('it-header-navbar-wrapper', $html);
        $this->assertStringContainsString('container-xxl', $html);
        $this->assertStringContainsString('<nav class="navbar navbar-expand-lg"', $html);
    }

    public function test_nav_class_contains_navbar_expand_lg(): void
    {
        $html = (string) $this->blade('<x-italia::header-navbar :items="[[\'text\' => \'Home\', \'url\' => \'/\']]" />');

        $this->assertStringContainsString('navbar navbar-expand-lg', $html);
    }

    public function test_items_render_in_navbar_nav(): void
    {
        $html = (string) $this->blade('<x-italia::header-navbar :items="[[\'text\' => \'Home\', \'url\' => \'/\'], [\'text\' => \'Novità\', \'url\' => \'/novita\']]" />');

        $this->assertStringContainsString('<ul class="navbar-nav">', $html);
        $this->assertStringContainsString('Home', $html);
        $this->assertStringContainsString('Novità', $html);
    }

    public function test_simple_link_renders_as_nav_link(): void
    {
        $html = (string) $this->blade('<x-italia::header-navbar :items="[[\'text\' => \'Home\', \'url\' => \'/\']]" />');

        $this->assertMatchesRegularExpression('/<li class="nav-item">\s*<a\s+class="nav-link"\s+href="\/"/s', $html);
    }

    public function test_active_link_has_active_class_and_aria_current(): void
    {
        $html = (string) $this->blade('<x-italia::header-navbar :items="[[\'text\' => \'Home\', \'url\' => \'/\', \'active\' => true]]" />');

        $this->assertMatchesRegularExpression('/<a\s+class="nav-link active"\s+href="\/"/s', $html);
        $this->assertStringContainsString('aria-current="page"', $html);
    }

    public function test_dropdown_renders_toggle_and_menu(): void
    {
        $html = (string) $this->blade('<x-italia::header-navbar :items="[[\'text\' => \'Amministrazione\', \'url\' => \'/amministrazione\', \'dropdown\' => [[\'text\' => \'Giunta\', \'url\' => \'/giunta\']]]]" />');

        $this->assertStringContainsString('<li class="nav-item dropdown">', $html);
        $this->assertStringContainsString('nav-link dropdown-toggle', $html);
        $this->assertStringContainsString('data-bs-toggle="dropdown"', $html);
        $this->assertStringContainsString('aria-expanded="false"', $html);
        $this->assertStringContainsString('dropdown-menu', $html);
    }

    public function test_dropdown_links_render(): void
    {
        $html = (string) $this->blade('<x-italia::header-navbar :items="[[\'text\' => \'Amministrazione\', \'url\' => \'/amministrazione\', \'dropdown\' => [[\'text\' => \'Giunta\', \'url\' => \'/giunta\'], [\'text\' => \'Consiglio\', \'url\' => \'/consiglio\']]]]" />');

        $this->assertStringContainsString('href="/giunta"', $html);
        $this->assertStringContainsString('href="/consiglio"', $html);
        $this->assertStringContainsString('dropdown-item list-item', $html);
    }

    public function test_megamenu_renders_has_megamenu_on_nav(): void
    {
        $html = (string) $this->blade('<x-italia::header-navbar :items="[[\'text\' => \'Servizi\', \'url\' => \'/servizi\', \'megamenu\' => [[\'heading\' => \'Anagrafe\', \'links\' => [[\'text\' => \'Certificati\', \'url\' => \'/certificati\']]]]]]" />');

        $this->assertStringContainsString('navbar navbar-expand-lg has-megamenu', $html);
        $this->assertStringContainsString('<li class="nav-item dropdown megamenu">', $html);
    }

    public function test_no_has_megamenu_without_megamenu_items(): void
    {
        $html = (string) $this->blade('<x-italia::header-navbar :items="[[\'text\' => \'Home\', \'url\' => \'/\']]" />');

        $this->assertStringNotContainsString('has-megamenu', $html);
    }

    public function test_megamenu_renders_sections_with_heading_and_links(): void
    {
        $html = (string) $this->blade('<x-italia::header-navbar :items="[[\'text\' => \'Servizi\', \'url\' => \'/servizi\', \'megamenu\' => [[\'heading\' => \'Anagrafe\', \'links\' => [[\'text\' => \'Certificati\', \'url\' => \'/certificati\'], [\'text\' => \'Residenza\', \'url\' => \'/residenza\']]], [\'heading\' => \'Tributi\', \'links\' => [[\'text\' => \'IMU\', \'url\' => \'/imu\']]]]]]" />');

        $this->assertStringContainsString('link-list-heading', $html);
        $this->assertStringContainsString('Anagrafe', $html);
        $this->assertStringContainsString('Tributi', $html);
        $this->assertStringContainsString('href="/certificati"', $html);
        $this->assertStringContainsString('href="/residenza"', $html);
        $this->assertStringContainsString('href="/imu"', $html);
        $this->assertStringContainsString('col-6 col-lg-4', $html);
    }

    public function test_menu_id_is_linked_to_aria_controls_and_target(): void
    {
        $html = (string) $this->blade('<x-italia::header-navbar :items="[[\'text\' => \'Home\', \'url\' => \'/\']]" />');

        preg_match('/aria-controls="(dlk-header-navbar-\d+-menu)"/', $html, $controls);

        $this->assertNotEmpty($controls);
        $this->assertMatchesRegularExpression('/id="' . $controls[1] . '"/', $html);
        $this->assertMatchesRegularExpression('/data-bs-target="#' . $controls[1] . '"/', $html);
    }

    public function test_menu_id_is_unique_per_instance(): void
    {
        $first = (string) $this->blade('<x-italia::header-navbar :items="[[\'text\' => \'Home\', \'url\' => \'/\']]" />');
        $second = (string) $this->blade('<x-italia::header-navbar :items="[[\'text\' => \'Home\', \'url\' => \'/\']]" />');

        preg_match('/id="(dlk-header-navbar-\d+-menu)"/', $first, $firstId);
        preg_match('/id="(dlk-header-navbar-\d+-menu)"/', $second, $secondId);

        $this->assertNotEmpty($firstId);
        $this->assertNotEmpty($secondId);
        $this->assertNotSame($firstId[1], $secondId[1]);
    }

    public function test_burger_uses_navbarcollapsible_toggle(): void
    {
        $html = (string) $this->blade('<x-italia::header-navbar :items="[[\'text\' => \'Home\', \'url\' => \'/\']]" />');

        $this->assertStringContainsString('custom-navbar-toggler', $html);
        $this->assertStringContainsString('data-bs-toggle="navbarcollapsible"', $html);
        $this->assertStringContainsString('sprites.svg#it-burger', $html);
        $this->assertStringContainsString('aria-label="Apri il menu"', $html);
    }

    public function test_close_button_renders(): void
    {
        $html = (string) $this->blade('<x-italia::header-navbar :items="[[\'text\' => \'Home\', \'url\' => \'/\']]" />');

        $this->assertStringContainsString('close-div', $html);
        $this->assertStringContainsString('btn close-menu', $html);
        $this->assertStringContainsString('sprites.svg#it-close', $html);
        $this->assertStringContainsString('Chiudi', $html);
    }

    public function test_light_renders_theme_light(): void
    {
        $html = (string) $this->blade('<x-italia::header-navbar :items="[[\'text\' => \'Home\', \'url\' => \'/\']]" light />');

        $this->assertStringContainsString('it-header-navbar-wrapper theme-light', $html);
    }

    public function test_sticky_renders_data_bs_toggle_sticky(): void
    {
        $html = (string) $this->blade('<x-italia::header-navbar :items="[[\'text\' => \'Home\', \'url\' => \'/\']]" sticky />');

        $this->assertStringContainsString('data-bs-toggle="sticky"', $html);
    }

    public function test_empty_items_render_nothing(): void
    {
        $html = (string) $this->blade('<x-italia::header-navbar />');

        $this->assertSame('', trim($html));
    }

    public function test_mixed_item_types_render(): void
    {
        $html = (string) $this->blade('<x-italia::header-navbar :items="[[\'text\' => \'Home\', \'url\' => \'/\', \'active\' => true], [\'text\' => \'Amministrazione\', \'url\' => \'/amministrazione\', \'dropdown\' => [[\'text\' => \'Giunta\', \'url\' => \'/giunta\']]], [\'text\' => \'Servizi\', \'url\' => \'/servizi\', \'megamenu\' => [[\'heading\' => \'Anagrafe\', \'links\' => [[\'text\' => \'Certificati\', \'url\' => \'/certificati\']]]]], [\'text\' => \'Novità\', \'url\' => \'/novita\']]" />');

        $this->assertMatchesRegularExpression('/<a\s+class="nav-link active"\s+href="\/"/s', $html);
        $this->assertStringContainsString('<li class="nav-item dropdown">', $html);
        $this->assertStringContainsString('<li class="nav-item dropdown megamenu">', $html);
        $this->assertStringContainsString('href="/novita"', $html);
        $this->assertStringContainsString('navbar navbar-expand-lg has-megamenu', $html);
    }

    public function test_dropdown_item_without_url_defaults_to_hash(): void
    {
        $html = (string) $this->blade('<x-italia::header-navbar :items="[[\'text\' => \'Amministrazione\', \'url\' => \'/amministrazione\', \'dropdown\' => [[\'text\' => \'Giunta\']]]]" />');

        $this->assertStringContainsString('href="#">Giunta', $html);
    }
}
