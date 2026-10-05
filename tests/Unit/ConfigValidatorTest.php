<?php

namespace IgorSmoleac\DesignLaravelKit\Tests\Unit;

use IgorSmoleac\DesignLaravelKit\DTO\CenterConfig;
use IgorSmoleac\DesignLaravelKit\DTO\ConfigValidator;
use IgorSmoleac\DesignLaravelKit\DTO\FooterConfig;
use IgorSmoleac\DesignLaravelKit\DTO\NavItemConfig;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class ConfigValidatorTest extends TestCase
{
    #[DataProvider('validUrlExamples')]
    public function test_assert_url_accepts_supported_link_forms(string $url): void
    {
        ConfigValidator::assertUrl($url, 'url');

        $this->assertSame($url, ConfigValidator::requiredUrl(['url' => $url], 'url'));
    }

    /** @return array<string, array{string}> */
    public static function validUrlExamples(): array
    {
        return [
            'https' => ['https://example.com'],
            'http' => ['http://example.com'],
            'uppercase scheme' => ['HTTPS://EXAMPLE.COM'],
            'non-ascii path' => ['https://www.comune.it/città'],
            'query string' => ['https://example.com/path?q=1&x=2'],
            'email' => ['mailto:info@example.it'],
            'telephone' => ['tel:+3906000000'],
            'empty anchor' => ['#'],
            'anchor' => ['#servizi'],
            'hyphenated anchor' => ['#main-content'],
            'root path' => ['/'],
            'root-relative path' => ['/servizi'],
            'root-relative path with query' => ['/it/servizi?x=1'],
            'relative path' => ['servizi'],
            'current-directory path' => ['./servizi'],
            'parent-directory path' => ['../servizi'],
            'html path' => ['servizi.html'],
        ];
    }

    #[DataProvider('invalidUrlExamples')]
    public function test_assert_url_rejects_unsupported_link_forms(string $url): void
    {
        try {
            ConfigValidator::assertUrl($url, 'url');
            $this->fail('Expected an invalid argument for the URL.');
        } catch (InvalidArgumentException $exception) {
            $this->assertStringContainsString("Field 'url'", $exception->getMessage());
            $this->assertStringContainsString("value '{$url}'", $exception->getMessage());
            $this->assertStringContainsString('Allowed: http(s), mailto:, tel:, #fragment, root-relative, relative path.', $exception->getMessage());
        }
    }

    /** @return array<string, array{string}> */
    public static function invalidUrlExamples(): array
    {
        return [
            'javascript' => ['javascript:alert(1)'],
            'uppercase javascript' => ['JavaScript:alert(1)'],
            'leading spaces' => ['  javascript:alert(1)'],
            'leading tabs' => ["\tjavascript:alert(1)"],
            'html data URI' => ['data:text/html,<script>'],
            'vbscript' => ['vbscript:msgbox(1)'],
            'file' => ['file:///etc/passwd'],
            'ftp' => ['ftp://example.com'],
            'empty' => [''],
            'plain text' => ['not a url'],
        ];
    }

    public function test_assert_url_truncates_long_values_in_error_messages(): void
    {
        $url = 'javascript:' . str_repeat('a', 90);

        try {
            ConfigValidator::assertUrl($url, 'url');
            $this->fail('Expected an invalid argument for the URL.');
        } catch (InvalidArgumentException $exception) {
            preg_match("/value '([^']+)'/", $exception->getMessage(), $matches);

            $this->assertLessThan(strlen($url), strlen($matches[1]));
            $this->assertLessThanOrEqual(80, strlen($matches[1]));
            $this->assertStringStartsWith('javascript:', $matches[1]);
            $this->assertStringEndsWith('...', $matches[1]);
            $this->assertStringNotContainsString($url, $exception->getMessage());
        }
    }

    public function test_required_url_rejects_null_as_a_missing_required_field(): void
    {
        $this->assertInvalidArgument(fn () => ConfigValidator::requiredUrl(['url' => null], 'url'), 'url');
    }

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
