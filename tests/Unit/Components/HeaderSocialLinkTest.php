<?php

namespace IgorSmoleac\DesignLaravelKit\Tests\Unit\Components;

use IgorSmoleac\DesignLaravelKit\Components\HeaderSocialLink;
use IgorSmoleac\DesignLaravelKit\DesignLaravelKitServiceProvider;
use Illuminate\View\ViewException;
use Orchestra\Testbench\TestCase;

class HeaderSocialLinkTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [DesignLaravelKitServiceProvider::class];
    }

    public function test_renders_without_custom_attributes(): void
    {
        $html = (string) $this->blade('<x-italia::header-social-link url="https://example.com" label="Social" />');

        $this->assertStringStartsWith('<li', $this->rootTag($html));
        $this->assertStringContainsString('href="https://example.com"', $html);
        $this->assertStringContainsString('target="_blank" rel="noopener noreferrer"', $html);
    }

    public function test_adds_custom_class_to_root_element(): void
    {
        $html = (string) $this->blade('<x-italia::header-social-link url="https://example.com" label="Social" class="custom" />');

        $this->assertStringContainsString('class="custom"', $this->rootTag($html));
    }

    public function test_adds_data_attribute_to_root_element(): void
    {
        $html = (string) $this->blade('<x-italia::header-social-link url="https://example.com" label="Social" data-analytics="x" />');

        $this->assertStringContainsString('data-analytics="x"', $this->rootTag($html));
    }

    public function test_adds_id_to_root_element(): void
    {
        $html = (string) $this->blade('<x-italia::header-social-link url="https://example.com" label="Social" id="anchor" />');

        $this->assertStringContainsString('id="anchor"', $this->rootTag($html));
    }

    public function test_missing_url_fails_during_construction(): void
    {
        $this->expectException(\ArgumentCountError::class);

        new HeaderSocialLink(label: 'Social');
    }

    public function test_missing_label_fails_during_construction(): void
    {
        $this->expectException(\ArgumentCountError::class);

        new HeaderSocialLink(url: 'https://example.com');
    }

    public function test_empty_label_is_rejected_during_render(): void
    {
        $this->expectException(ViewException::class);
        $this->expectExceptionMessage("Field 'label'");

        $this->blade('<x-italia::header-social-link url="https://example.com" label="" />');
    }

    private function rootTag(string $html): string
    {
        return explode('>', trim($html), 2)[0];
    }
}
