<?php

namespace IgorSmoleac\DesignLaravelKit\Tests\Unit\Components;

use IgorSmoleac\DesignLaravelKit\DesignLaravelKitServiceProvider;
use Orchestra\Testbench\TestCase;

class LayoutTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [DesignLaravelKitServiceProvider::class];
    }

    private function navbar(): array
    {
        return [
            'items' => [
                ['text' => 'Home', 'url' => '/'],
            ],
        ];
    }

    private function footer(): array
    {
        return [
            'title' => 'Comune di Roma',
            'legalLinks' => [
                ['url' => '/privacy', 'text' => 'Privacy policy', 'dataElement' => 'privacy-policy-link'],
            ],
        ];
    }

    public function test_renders_doctype_and_html_lang(): void
    {
        config(['app.locale' => 'it']);

        $html = (string) $this->blade('<x-italia::layout title="Home" />');

        $this->assertStringContainsString('<!DOCTYPE html>', $html);
        $this->assertStringContainsString('<html lang="it">', $html);
    }

    public function test_title_renders_in_title_tag(): void
    {
        $html = (string) $this->blade('<x-italia::layout title="Home — Comune di Roma" />');

        $this->assertStringContainsString('<title>Home — Comune di Roma</title>', $html);
    }

    public function test_title_falls_back_to_app_name(): void
    {
        config(['app.name' => 'Portale PA']);

        $html = (string) $this->blade('<x-italia::layout />');

        $this->assertStringContainsString('<title>Portale PA</title>', $html);
    }

    public function test_description_renders_as_meta(): void
    {
        $html = (string) $this->blade('<x-italia::layout title="Home" description="Portale istituzionale" />');

        $this->assertStringContainsString('<meta name="description" content="Portale istituzionale">', $html);
    }

    public function test_no_description_meta_without_description(): void
    {
        $html = (string) $this->blade('<x-italia::layout title="Home" />');

        $this->assertStringNotContainsString('name="description"', $html);
    }

    public function test_lang_prop_overrides_locale(): void
    {
        config(['app.locale' => 'it']);

        $html = (string) $this->blade('<x-italia::layout title="Home" lang="en" />');

        $this->assertStringContainsString('<html lang="en">', $html);
    }

    public function test_charset_and_viewport_are_present(): void
    {
        $html = (string) $this->blade('<x-italia::layout title="Home" />');

        $this->assertStringContainsString('<meta charset="utf-8">', $html);
        $this->assertStringContainsString('<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">', $html);
    }

    public function test_skip_link_renders_by_default(): void
    {
        $html = (string) $this->blade('<x-italia::layout title="Home" />');

        $this->assertStringContainsString('class="visually-hidden-focusable" href="#main"', $html);
        $this->assertStringContainsString('Vai al contenuto principale', $html);
    }

    public function test_skip_link_can_be_disabled(): void
    {
        $html = (string) $this->blade('<x-italia::layout title="Home" :skip-to-content="false" />');

        $this->assertStringNotContainsString('visually-hidden-focusable', $html);
    }

    public function test_skip_label_is_customizable(): void
    {
        $html = (string) $this->blade('<x-italia::layout title="Home" skip-label="Salta al contenuto" />');

        $this->assertStringContainsString('>Salta al contenuto</a>', $html);
    }

    public function test_main_has_id_main(): void
    {
        $html = (string) $this->blade('<x-italia::layout title="Home" />');

        $this->assertStringContainsString('<main id="main" class="container my-4">', $html);
    }

    public function test_main_class_is_overridable(): void
    {
        $html = (string) $this->blade('<x-italia::layout title="Home" main-class="container-fluid px-0" />');

        $this->assertStringContainsString('<main id="main" class="container-fluid px-0">', $html);
    }

    public function test_header_renders_inside(): void
    {
        $html = (string) $this->blade('<x-italia::layout title="Home" :navbar="$navbar" sticky />', ['navbar' => $this->navbar()]);

        $this->assertStringContainsString('it-header-wrapper', $html);
        $this->assertStringContainsString('it-header-navbar-wrapper', $html);
        $this->assertStringContainsString('data-bs-toggle="sticky"', $html);
    }

    public function test_footer_renders_inside(): void
    {
        $html = (string) $this->blade('<x-italia::layout title="Home" :footer="$footer" />', ['footer' => $this->footer()]);

        $this->assertStringContainsString('<footer class="it-footer">', $html);
        $this->assertStringContainsString('Comune di Roma', $html);
        $this->assertStringContainsString('data-element="privacy-policy-link"', $html);
    }

    public function test_footer_title_falls_back_to_app_name(): void
    {
        config(['app.name' => 'Portale PA']);

        $html = (string) $this->blade('<x-italia::layout title="Home" />');

        $this->assertStringContainsString('<h2 class="no_toc">Portale PA</h2>', $html);
    }

    public function test_slot_content_renders_inside_main(): void
    {
        $html = (string) $this->blade('<x-italia::layout title="Home"><h1>Benvenuto</h1></x-italia::layout>');

        $main = substr($html, (int) strpos($html, '<main id="main"'));

        $this->assertStringContainsString('<h1>Benvenuto</h1>', $main);
    }

    public function test_breadcrumbs_slot_renders_before_main(): void
    {
        $html = (string) $this->blade(
            '<x-italia::layout title="Home"><x-slot:breadcrumbs><ol class="breadcrumb"><li class="breadcrumb-item">Home</li></ol></x-slot:breadcrumbs><p>Contenuto</p></x-italia::layout>'
        );

        $this->assertStringContainsString('breadcrumb', $html);
        $this->assertLessThan(strpos($html, '<main id="main"'), strpos($html, 'class="breadcrumb"'));
    }

    public function test_no_breadcrumbs_without_slot(): void
    {
        $html = (string) $this->blade('<x-italia::layout title="Home" />');

        $this->assertStringNotContainsString('breadcrumb', $html);
    }

    public function test_body_class_is_added(): void
    {
        $html = (string) $this->blade('<x-italia::layout title="Home" body-class="it-page-custom" />');

        $this->assertStringContainsString('<body class="bg-white it-page-custom">', $html);
    }

    public function test_body_has_base_class(): void
    {
        $html = (string) $this->blade('<x-italia::layout title="Home" />');

        $this->assertStringContainsString('<body class="bg-white">', $html);
    }

    public function test_styles_and_scripts_directives_are_present(): void
    {
        $html = (string) $this->blade('<x-italia::layout title="Home" />');

        $this->assertStringContainsString('design-laravel-kit.css', $html);
        $this->assertStringContainsString('design-laravel-kit.js', $html);
        $this->assertLessThan(strpos($html, '</body>'), strpos($html, 'design-laravel-kit.js'));
    }

    public function test_light_is_forwarded_to_header_and_footer(): void
    {
        $html = (string) $this->blade('<x-italia::layout title="Home" :navbar="$navbar" :footer="$footer" light />', [
            'navbar' => $this->navbar(),
            'footer' => $this->footer(),
        ]);

        $this->assertSame(3, substr_count($html, 'theme-light'));
    }
}
