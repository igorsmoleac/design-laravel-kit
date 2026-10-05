<?php

namespace IgorSmoleac\DesignLaravelKit\Tests\Unit\Components;

use IgorSmoleac\DesignLaravelKit\Components\Header;
use IgorSmoleac\DesignLaravelKit\DesignLaravelKitServiceProvider;
use Illuminate\View\ViewException;
use Orchestra\Testbench\TestCase;

class HeaderTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [DesignLaravelKitServiceProvider::class];
    }

    public function test_renders_header_parts_from_named_slots(): void
    {
        $html = (string) $this->blade(<<<'BLADE'
<x-italia::header>
    <x-slot:slim ente="Comune di Roma" login-url="/login"></x-slot:slim>
    <x-slot:center title="Comune di Roma" tagline="Portale istituzionale" search-url="/search"></x-slot:center>
    <x-slot:navbar><x-italia::header-nav-item text="Home" url="/" /></x-slot:navbar>
</x-italia::header>
BLADE);

        $this->assertStringContainsString('it-header-slim-wrapper', $html);
        $this->assertStringContainsString('it-header-center-wrapper', $html);
        $this->assertStringContainsString('it-header-navbar-wrapper', $html);
        $this->assertStringContainsString('href="/login"', $html);
        $this->assertStringContainsString('href="/search"', $html);
        $this->assertStringContainsString('href="/"', $html);
    }

    public function test_empty_header_renders_bare_wrapper(): void
    {
        $html = (string) $this->blade('<x-italia::header />');

        $this->assertStringContainsString('class="it-header-wrapper"', $html);
        $this->assertStringNotContainsString('it-header-slim-wrapper', $html);
        $this->assertStringNotContainsString('it-header-center-wrapper', $html);
        $this->assertStringNotContainsString('it-header-navbar-wrapper', $html);
    }

    public function test_old_array_attributes_are_rejected(): void
    {
        $this->expectException(ViewException::class);
        $this->expectExceptionMessage("The 'slim' array attribute is no longer supported");

        $this->blade('<x-italia::header :slim="[\'ente\' => \'Comune di Roma\']" />');
    }

    public function test_center_slot_without_title_renders_without_brand_text(): void
    {
        $html = (string) $this->blade('<x-italia::header><x-slot:center></x-slot:center></x-italia::header>');

        $this->assertStringContainsString('it-header-center-wrapper', $html);
        $this->assertStringNotContainsString('it-brand-text', $html);
    }

    public function test_sticky_light_and_small_options_are_applied(): void
    {
        $html = (string) $this->blade('<x-italia::header sticky light small />');

        $this->assertStringContainsString('it-header-wrapper it-header-sticky it-header-small theme-light', $html);
        $this->assertStringContainsString('data-bs-toggle="sticky"', $html);
    }

    public function test_child_sticky_toggles_are_not_duplicated(): void
    {
        $html = (string) $this->blade(<<<'BLADE'
<x-italia::header sticky>
    <x-slot:slim ente="Comune" sticky="true"></x-slot:slim>
    <x-slot:navbar sticky="true"><x-italia::header-nav-item text="Home" url="/" /></x-slot:navbar>
</x-italia::header>
BLADE);

        $this->assertSame(1, substr_count($html, 'data-bs-toggle="sticky"'));
    }

    public function test_header_class_flags_can_be_created_directly(): void
    {
        $header = new Header(light: true, small: true);

        $this->assertSame('it-header-wrapper it-header-small theme-light', $header->wrapperClass());
    }

    public function test_slot_attribute_url_is_validated(): void
    {
        $this->expectException(ViewException::class);
        $this->expectExceptionMessage("Field 'searchUrl'");

        $this->blade('<x-italia::header><x-slot:center title="Ente" search-url="bad url"></x-slot:center></x-italia::header>');
    }

    public function test_custom_id_and_data_attributes_are_forwarded(): void
    {
        $html = (string) $this->blade('<x-italia::header id="site-header" data-element="header" />');

        $this->assertStringContainsString('id="site-header"', $html);
        $this->assertStringContainsString('data-element="header"', $html);
    }

    public function test_small_flag_is_forwarded_to_center_slot(): void
    {
        $html = (string) $this->blade('<x-italia::header small><x-slot:center title="Comune"></x-slot:center></x-italia::header>');

        $this->assertStringContainsString('it-header-center-wrapper it-small-header', $html);
    }

    public function test_light_flag_is_forwarded_to_each_header_part(): void
    {
        $html = (string) $this->blade(<<<'BLADE'
<x-italia::header light>
    <x-slot:slim ente="Comune"></x-slot:slim>
    <x-slot:center title="Comune"></x-slot:center>
    <x-slot:navbar><x-italia::header-nav-item text="Home" url="/" /></x-slot:navbar>
</x-italia::header>
BLADE);

        $this->assertStringContainsString('it-header-wrapper theme-light', $html);
        $this->assertStringContainsString('it-header-slim-wrapper theme-light', $html);
        $this->assertStringContainsString('it-header-center-wrapper theme-light', $html);
        $this->assertStringContainsString('it-header-navbar-wrapper theme-light', $html);
    }

    public function test_slim_slot_renders_independently(): void
    {
        $html = (string) $this->blade('<x-italia::header><x-slot:slim ente="Comune"></x-slot:slim></x-italia::header>');

        $this->assertStringContainsString('it-header-slim-wrapper', $html);
        $this->assertStringNotContainsString('it-nav-wrapper', $html);
    }

    public function test_center_slot_renders_independently(): void
    {
        $html = (string) $this->blade('<x-italia::header><x-slot:center title="Comune"></x-slot:center></x-italia::header>');

        $this->assertStringContainsString('it-header-center-wrapper', $html);
        $this->assertStringNotContainsString('it-header-slim-wrapper', $html);
        $this->assertStringNotContainsString('it-header-navbar-wrapper', $html);
    }

    public function test_navbar_slot_renders_independently(): void
    {
        $html = (string) $this->blade('<x-italia::header><x-slot:navbar><x-italia::header-nav-item text="Home" url="/" /></x-slot:navbar></x-italia::header>');

        $this->assertStringContainsString('it-header-navbar-wrapper', $html);
        $this->assertStringNotContainsString('it-header-slim-wrapper', $html);
        $this->assertStringNotContainsString('it-header-center-wrapper', $html);
    }

    public function test_empty_content_in_configured_named_slots_keeps_configured_parts(): void
    {
        $html = (string) $this->blade(<<<'BLADE'
<x-italia::header>
    <x-slot:slim ente="Comune"></x-slot:slim>
    <x-slot:center title="Comune"></x-slot:center>
    <x-slot:navbar></x-slot:navbar>
</x-italia::header>
BLADE);

        $this->assertStringContainsString('it-header-slim-wrapper', $html);
        $this->assertStringContainsString('it-header-center-wrapper', $html);
        $this->assertStringNotContainsString('it-header-navbar-wrapper', $html);
        $this->assertStringContainsString('it-nav-wrapper', $html);
    }

    public function test_small_option_affects_only_center_wrapper_class(): void
    {
        $header = new Header(small: true);

        $this->assertSame('it-header-wrapper it-header-small', $header->wrapperClass());
    }

    public function test_slim_only_header_omits_navigation_wrapper(): void
    {
        $html = (string) $this->blade('<x-italia::header><x-slot:slim ente="Comune"></x-slot:slim></x-italia::header>');

        $this->assertStringContainsString('it-header-slim-wrapper', $html);
        $this->assertStringNotContainsString('it-nav-wrapper', $html);
    }

    public function test_small_and_light_flags_apply_to_center_component(): void
    {
        $html = (string) $this->blade('<x-italia::header small light><x-slot:center title="Comune"></x-slot:center></x-italia::header>');

        $this->assertStringContainsString('it-header-center-wrapper it-small-header theme-light', $html);
    }

    public function test_custom_attributes_are_forwarded_to_header_wrapper(): void
    {
        $html = (string) $this->blade('<x-italia::header id="site&amp;header" data-label="portal" />');

        $this->assertStringContainsString('id="site&amp;header"', $html);
        $this->assertStringContainsString('data-label="portal"', $html);
    }
}
