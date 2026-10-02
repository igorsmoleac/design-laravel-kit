<?php

namespace IgorSmoleac\DesignLaravelKit\Tests\Unit;

use IgorSmoleac\DesignLaravelKit\DTO\CenterConfig;
use IgorSmoleac\DesignLaravelKit\DTO\ConfigValidator;
use IgorSmoleac\DesignLaravelKit\DTO\FooterConfig;
use IgorSmoleac\DesignLaravelKit\DTO\NavItemConfig;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class ConfigValidatorTest extends TestCase
{
    public function test_logo_url_accepts_svg_image_data_uri(): void
    {
        $logo = 'data:image/svg+xml;base64,PHN2Zy8+';

        $this->assertSame($logo, ConfigValidator::logoUrl($logo, 'logo'));
    }

    public function test_logo_url_accepts_png_image_data_uri(): void
    {
        $logo = 'data:image/png;base64,iVBORw0KGgo=';

        $this->assertSame($logo, ConfigValidator::logoUrl($logo, 'logo'));
    }

    public function test_logo_url_rejects_text_data_uri(): void
    {
        $this->assertInvalidArgument(
            fn () => ConfigValidator::logoUrl('data:text/html;base64,PGgxPg==', 'logo'),
            'logo',
        );
    }

    public function test_logo_url_rejects_application_data_uri(): void
    {
        $this->assertInvalidArgument(
            fn () => ConfigValidator::logoUrl('data:application/json;base64,e30=', 'logo'),
            'logo',
        );
    }

    public function test_logo_url_accepts_http_and_root_relative_urls(): void
    {
        $this->assertSame('https://example.com/logo.svg', ConfigValidator::logoUrl('https://example.com/logo.svg', 'logo'));
        $this->assertSame('/images/logo.svg', ConfigValidator::logoUrl('/images/logo.svg', 'logo'));
    }

    public function test_logo_url_rejects_javascript_scheme(): void
    {
        $this->assertInvalidArgument(
            fn () => ConfigValidator::logoUrl('javascript:alert(1)', 'logo'),
            'logo',
        );
    }

    public function test_empty_optional_logo_values_are_preserved(): void
    {
        $center = CenterConfig::fromArray(['title' => 'Comune', 'logo' => '']);
        $footer = FooterConfig::fromArray(['title' => 'Comune', 'logo' => '']);

        $this->assertSame('', $center->logo);
        $this->assertSame('', $footer->logo);
    }

    public function test_center_and_footer_configs_accept_image_data_uri_logos(): void
    {
        $logo = 'data:image/svg+xml;base64,PHN2Zy8+';
        $center = CenterConfig::fromArray(['title' => 'Comune', 'logo' => $logo]);
        $footer = FooterConfig::fromArray(['title' => 'Comune', 'logo' => $logo]);

        $this->assertSame($logo, $center->logo);
        $this->assertSame($logo, $footer->logo);
    }

    public function test_regular_url_fields_still_reject_image_data_uri(): void
    {
        $dataUri = 'data:image/svg+xml;base64,PHN2Zy8+';

        $this->assertInvalidArgument(
            fn () => CenterConfig::fromArray(['title' => 'Comune', 'url' => $dataUri]),
            'url',
        );
        $this->assertInvalidArgument(
            fn () => NavItemConfig::fromArray(['text' => 'Logo', 'url' => $dataUri]),
            'url',
        );
    }

    private function assertInvalidArgument(callable $operation, string $field): void
    {
        try {
            $operation();
            $this->fail("Expected an invalid argument for field '{$field}'.");
        } catch (InvalidArgumentException $exception) {
            $this->assertStringContainsString($field, $exception->getMessage());
        }
    }
}
