<?php

namespace IgorSmoleac\DesignLaravelKit\Tests\Unit\Components;

use IgorSmoleac\DesignLaravelKit\Components\Header;
use IgorSmoleac\DesignLaravelKit\DesignLaravelKitServiceProvider;
use Orchestra\Testbench\TestCase;

class HeaderTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [DesignLaravelKitServiceProvider::class];
    }

    private function slim(): array
    {
        return [
            'ente' => 'Comune di Roma',
            'enteUrl' => 'https://www.comune.roma.it',
            'links' => [
                ['url' => '/', 'text' => 'Pagina iniziale'],
            ],
            'languages' => [
                ['code' => 'it', 'label' => 'ITA', 'active' => true],
            ],
            'loginUrl' => '/login',
        ];
    }

    private function center(): array
    {
        return [
            'title' => 'Comune di Roma',
            'tagline' => 'Portale istituzionale',
            'searchUrl' => '/search',
        ];
    }

    private function navbar(): array
    {
        return [
            'items' => [
                ['text' => 'Home', 'url' => '/'],
            ],
        ];
    }

    public function test_renders_it_header_wrapper(): void
    {
        $html = (string) $this->blade('<x-italia::header />');

        $this->assertStringContainsString('it-header-wrapper', $html);
    }

    public function test_sticky_adds_class_and_data_toggle(): void
    {
        $html = (string) $this->blade('<x-italia::header :center="$center" sticky />', ['center' => $this->center()]);

        $this->assertStringContainsString('it-header-wrapper it-header-sticky', $html);
        $this->assertStringContainsString('data-bs-toggle="sticky"', $html);
    }

    public function test_light_adds_theme_light_to_wrapper(): void
    {
        $html = (string) $this->blade('<x-italia::header light />');

        $this->assertStringContainsString('it-header-wrapper theme-light', $html);
    }

    public function test_small_adds_it_header_small(): void
    {
        $html = (string) $this->blade('<x-italia::header small />');

        $this->assertStringContainsString('it-header-wrapper it-header-small', $html);
    }

    public function test_small_is_passed_to_center(): void
    {
        $html = (string) $this->blade('<x-italia::header :center="$center" small />', ['center' => $this->center()]);

        $this->assertStringContainsString('it-header-center-wrapper it-small-header', $html);
    }

    public function test_renders_slim_when_provided(): void
    {
        $html = (string) $this->blade('<x-italia::header :slim="$slim" />', ['slim' => $this->slim()]);

        $this->assertStringContainsString('it-header-slim-wrapper', $html);
        $this->assertStringContainsString('Comune di Roma', $html);
        $this->assertStringContainsString('href="/login"', $html);
    }

    public function test_does_not_render_slim_when_empty(): void
    {
        $html = (string) $this->blade('<x-italia::header :center="$center" />', ['center' => $this->center()]);

        $this->assertStringNotContainsString('it-header-slim-wrapper', $html);
    }

    public function test_renders_center_when_provided(): void
    {
        $html = (string) $this->blade('<x-italia::header :center="$center" />', ['center' => $this->center()]);

        $this->assertStringContainsString('it-header-center-wrapper', $html);
        $this->assertStringContainsString('Portale istituzionale', $html);
        $this->assertStringContainsString('href="/search"', $html);
    }

    public function test_does_not_render_center_when_empty(): void
    {
        $html = (string) $this->blade('<x-italia::header :slim="$slim" />', ['slim' => $this->slim()]);

        $this->assertStringNotContainsString('it-header-center-wrapper', $html);
    }

    public function test_renders_navbar_when_provided(): void
    {
        $html = (string) $this->blade('<x-italia::header :navbar="$navbar" />', ['navbar' => $this->navbar()]);

        $this->assertStringContainsString('it-header-navbar-wrapper', $html);
        $this->assertStringContainsString('<ul class="navbar-nav">', $html);
    }

    public function test_does_not_render_navbar_when_empty(): void
    {
        $html = (string) $this->blade('<x-italia::header :slim="$slim" />', ['slim' => $this->slim()]);

        $this->assertStringNotContainsString('it-header-navbar-wrapper', $html);
    }

    public function test_light_is_forwarded_to_all_parts(): void
    {
        $html = (string) $this->blade('<x-italia::header :slim="$slim" :center="$center" :navbar="$navbar" light />', [
            'slim' => $this->slim(),
            'center' => $this->center(),
            'navbar' => $this->navbar(),
        ]);

        $this->assertSame(4, substr_count($html, 'theme-light'));
        $this->assertStringContainsString('it-header-wrapper theme-light', $html);
        $this->assertStringContainsString('it-header-slim-wrapper theme-light', $html);
        $this->assertStringContainsString('it-header-center-wrapper theme-light', $html);
        $this->assertStringContainsString('it-header-navbar-wrapper theme-light', $html);
    }

    public function test_renders_all_parts_together(): void
    {
        $html = (string) $this->blade('<x-italia::header :slim="$slim" :center="$center" :navbar="$navbar" />', [
            'slim' => $this->slim(),
            'center' => $this->center(),
            'navbar' => $this->navbar(),
        ]);

        $this->assertStringContainsString('it-header-slim-wrapper', $html);
        $this->assertStringContainsString('it-header-center-wrapper', $html);
        $this->assertStringContainsString('it-header-navbar-wrapper', $html);
    }

    public function test_center_and_navbar_are_siblings_inside_it_nav_wrapper(): void
    {
        $html = (string) $this->blade('<x-italia::header :center="$center" :navbar="$navbar" />', [
            'center' => $this->center(),
            'navbar' => $this->navbar(),
        ]);

        $navWrapper = substr($html, strpos($html, 'it-nav-wrapper'));

        $this->assertStringContainsString('it-header-center-wrapper', $navWrapper);
        $this->assertStringContainsString('it-header-navbar-wrapper', $navWrapper);
        $this->assertLessThan(strpos($navWrapper, 'it-header-navbar-wrapper'), strpos($navWrapper, 'it-header-center-wrapper'));
    }

    public function test_navbar_only_renders_inside_it_nav_wrapper(): void
    {
        $html = (string) $this->blade('<x-italia::header :navbar="$navbar" />', ['navbar' => $this->navbar()]);

        $this->assertStringContainsString('it-nav-wrapper', $html);
        $this->assertStringContainsString('it-header-navbar-wrapper', $html);
        $this->assertStringNotContainsString('it-header-slim-wrapper', $html);
        $this->assertStringNotContainsString('it-header-center-wrapper', $html);
    }

    public function test_slim_only_renders_without_it_nav_wrapper(): void
    {
        $html = (string) $this->blade('<x-italia::header :slim="$slim" />', ['slim' => $this->slim()]);

        $this->assertStringContainsString('it-header-slim-wrapper', $html);
        $this->assertStringNotContainsString('it-nav-wrapper', $html);
    }

    public function test_empty_header_renders_bare_wrapper(): void
    {
        $html = (string) $this->blade('<x-italia::header />');

        $this->assertStringNotContainsString('it-header-slim-wrapper', $html);
        $this->assertStringNotContainsString('it-header-center-wrapper', $html);
        $this->assertStringNotContainsString('it-header-navbar-wrapper', $html);
        $this->assertStringNotContainsString('it-nav-wrapper', $html);
        $this->assertSame(1, substr_count(trim($html), 'it-header-wrapper'));
    }

    public function test_children_do_not_render_their_own_sticky_toggle(): void
    {
        $html = (string) $this->blade('<x-italia::header :slim="$slim" :navbar="$navbar" sticky />', [
            'slim' => $this->slim(),
            'navbar' => $this->navbar(),
        ]);

        $this->assertSame(1, substr_count($html, 'data-bs-toggle="sticky"'));
    }

    public function test_merge_slim_props_forces_light_and_disables_sticky(): void
    {
        $header = new Header(slim: ['ente' => 'Comune di Roma', 'sticky' => true], light: true);

        $this->assertSame([
            'ente' => 'Comune di Roma',
            'sticky' => false,
            'light' => true,
        ], $header->mergeSlimProps());
    }

    public function test_merge_center_props_applies_small(): void
    {
        $header = new Header(center: ['title' => 'Comune di Roma'], small: true);

        $this->assertSame([
            'title' => 'Comune di Roma',
            'light' => false,
            'small' => true,
        ], $header->mergeCenterProps());
    }

    public function test_merge_navbar_props_forces_light_and_disables_sticky(): void
    {
        $header = new Header(navbar: ['items' => [['text' => 'Home']], 'sticky' => true], light: true);

        $this->assertSame([
            'items' => [['text' => 'Home']],
            'sticky' => false,
            'light' => true,
        ], $header->mergeNavbarProps());
    }
}
