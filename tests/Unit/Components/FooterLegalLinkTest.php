<?php

namespace IgorSmoleac\DesignLaravelKit\Tests\Unit\Components;

use IgorSmoleac\DesignLaravelKit\Components\FooterLegalLink;
use IgorSmoleac\DesignLaravelKit\DesignLaravelKitServiceProvider;
use Orchestra\Testbench\TestCase;

class FooterLegalLinkTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [DesignLaravelKitServiceProvider::class];
    }

    public function test_renders_without_custom_attributes(): void
    {
        $html = (string) $this->blade('<x-italia::footer-legal-link url="/privacy" text="Privacy" />');

        $this->assertStringContainsString('class="list-inline-item"', $this->rootTag($html));
        $this->assertStringContainsString('href="/privacy"', $html);
        $this->assertStringContainsString('>Privacy</a>', $html);
    }

    public function test_adds_custom_class_to_root_element(): void
    {
        $html = (string) $this->blade('<x-italia::footer-legal-link url="/privacy" text="Privacy" class="custom" />');

        $this->assertStringContainsString('class="custom"', $this->rootTag($html));
    }

    public function test_adds_data_attribute_to_root_element(): void
    {
        $html = (string) $this->blade('<x-italia::footer-legal-link url="/privacy" text="Privacy" data-analytics="x" />');

        $this->assertStringContainsString('data-analytics="x"', $this->rootTag($html));
    }

    public function test_adds_id_to_root_element(): void
    {
        $html = (string) $this->blade('<x-italia::footer-legal-link url="/privacy" text="Privacy" id="anchor" />');

        $this->assertStringContainsString('id="anchor"', $this->rootTag($html));
    }

    public function test_missing_text_fails_during_construction(): void
    {
        $this->expectException(\ArgumentCountError::class);

        new FooterLegalLink(url: '/privacy');
    }

    public function test_missing_url_fails_during_construction(): void
    {
        $this->expectException(\ArgumentCountError::class);

        new FooterLegalLink(text: 'Privacy');
    }

    private function rootTag(string $html): string
    {
        return explode('>', trim($html), 2)[0];
    }
}
