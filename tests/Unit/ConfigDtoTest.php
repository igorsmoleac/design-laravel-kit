<?php

namespace IgorSmoleac\DesignLaravelKit\Tests\Unit;

use IgorSmoleac\DesignLaravelKit\DTO\CenterConfig;
use IgorSmoleac\DesignLaravelKit\DTO\FooterConfig;
use IgorSmoleac\DesignLaravelKit\DTO\LegalLinkConfig;
use IgorSmoleac\DesignLaravelKit\DTO\NavbarConfig;
use IgorSmoleac\DesignLaravelKit\DTO\NavItemConfig;
use IgorSmoleac\DesignLaravelKit\DTO\SlimConfig;
use IgorSmoleac\DesignLaravelKit\DTO\SocialLinkConfig;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class ConfigDtoTest extends TestCase
{
    public function test_from_array_creates_readonly_configuration_objects(): void
    {
        $slim = SlimConfig::fromArray(['ente' => 'Comune di Roma', 'ente-url' => 'https://www.comune.roma.it']);
        $center = CenterConfig::fromArray(['title' => 'Comune di Roma', 'search-url' => '/search']);
        $navbar = NavbarConfig::fromArray(['items' => [['text' => 'Home', 'url' => '/']]]);
        $footer = FooterConfig::fromArray([
            'title' => 'Comune di Roma',
            'legal-links' => [['url' => '/privacy', 'text' => 'Privacy']],
            'social' => [['url' => 'https://example.com', 'label' => 'Social', 'icon' => 'it-link']],
            'sections' => [['title' => 'Amministrazione']],
            'contacts' => [['value' => 'Piazza del Campidoglio']],
        ]);

        $this->assertSame('Comune di Roma', $slim->ente);
        $this->assertSame('/search', $center->searchUrl);
        $this->assertInstanceOf(NavItemConfig::class, $navbar->items[0]);
        $this->assertInstanceOf(LegalLinkConfig::class, $footer->legalLinks[0]);
        $this->assertInstanceOf(SocialLinkConfig::class, $footer->social[0]);

        foreach ([SlimConfig::class, CenterConfig::class, NavbarConfig::class, NavItemConfig::class, FooterConfig::class, LegalLinkConfig::class, SocialLinkConfig::class] as $class) {
            $this->assertTrue((new ReflectionClass($class))->isReadOnly());
        }
    }

    public function test_all_config_dtos_accept_safe_url_forms(): void
    {
        $slim = SlimConfig::fromArray(['ente' => 'Comune', 'ente-url' => 'https://www.comune.it/città']);
        $center = CenterConfig::fromArray(['title' => 'Comune', 'url' => '#', 'search-url' => '../servizi']);
        $navbar = NavbarConfig::fromArray(['items' => [['text' => 'Servizi', 'url' => 'servizi.html']]]);
        $footer = FooterConfig::fromArray([
            'title' => 'Comune',
            'url' => 'tel:+3906000000',
            'legal-links' => [['url' => 'mailto:info@example.it', 'text' => 'Privacy']],
            'social' => [['url' => './social', 'label' => 'Social']],
            'sections' => [['title' => 'Servizi', 'url' => '/it/servizi']],
        ]);

        $this->assertSame('https://www.comune.it/città', $slim->enteUrl);
        $this->assertSame('#', $center->url);
        $this->assertSame('servizi.html', $navbar->items[0]->url);
        $this->assertSame('mailto:info@example.it', $footer->legalLinks[0]->url);
        $this->assertSame('./social', $footer->social[0]->url);
        $this->assertSame('/it/servizi', $footer->sections[0]['url']);
    }

    public function test_nested_lists_become_typed_dtos(): void
    {
        $navbar = NavbarConfig::fromArray([
            'items' => [new NavItemConfig('Home', '/')],
        ]);
        $footer = FooterConfig::fromArray([
            'title' => 'Comune',
            'legalLinks' => [new LegalLinkConfig('/privacy', 'Privacy')],
            'social' => [new SocialLinkConfig('https://example.com', 'Social')],
        ]);

        $this->assertInstanceOf(NavItemConfig::class, $navbar->items[0]);
        $this->assertInstanceOf(LegalLinkConfig::class, $footer->legalLinks[0]);
        $this->assertInstanceOf(SocialLinkConfig::class, $footer->social[0]);
    }

    public function test_empty_required_fields_name_the_invalid_field(): void
    {
        $this->assertInvalidArgument(fn () => SlimConfig::fromArray(['ente' => '']), 'ente');
        $this->assertInvalidArgument(fn () => CenterConfig::fromArray(['title' => '  ']), 'title');
        $this->assertInvalidArgument(fn () => NavItemConfig::fromArray(['text' => '', 'url' => '/']), 'text');
        $this->assertInvalidArgument(fn () => NavItemConfig::fromArray(['text' => 'Home', 'url' => '']), 'url');
        $this->assertInvalidArgument(fn () => LegalLinkConfig::fromArray(['url' => '', 'text' => 'Privacy']), 'url');
        $this->assertInvalidArgument(fn () => LegalLinkConfig::fromArray(['url' => '/privacy', 'text' => '']), 'text');
        $this->assertInvalidArgument(fn () => SocialLinkConfig::fromArray(['url' => '', 'label' => 'Social']), 'url');
        $this->assertInvalidArgument(fn () => SocialLinkConfig::fromArray(['url' => '/social', 'label' => '']), 'label');
        $this->assertInvalidArgument(fn () => FooterConfig::fromArray(['title' => '']), 'title');
    }

    public function test_invalid_http_urls_name_the_invalid_field(): void
    {
        $this->assertInvalidArgument(fn () => SlimConfig::fromArray(['ente' => 'Comune', 'ente-url' => 'not a url']), 'enteUrl');
        $this->assertInvalidArgument(fn () => CenterConfig::fromArray(['title' => 'Comune', 'search-url' => 'javascript:alert(1)']), 'searchUrl');
        $this->assertInvalidArgument(fn () => NavItemConfig::fromArray(['text' => 'Home', 'url' => 'bad url']), 'url');
        $this->assertInvalidArgument(fn () => LegalLinkConfig::fromArray(['url' => 'bad url', 'text' => 'Privacy']), 'url');
        $this->assertInvalidArgument(fn () => FooterConfig::fromArray(['title' => 'Comune', 'url' => 'bad url']), 'url');
        $this->assertInvalidArgument(fn () => FooterConfig::fromArray(['title' => 'Comune', 'legal-links' => [['url' => 'bad url', 'text' => 'Privacy']]]), 'url');
        $this->assertInvalidArgument(fn () => FooterConfig::fromArray(['title' => 'Comune', 'sections' => [['url' => 'bad url']]]), 'sections.0.url');
        $this->assertInvalidArgument(fn () => SocialLinkConfig::fromArray(['url' => 'bad url', 'label' => 'Social']), 'url');
    }

    public function test_root_relative_urls_are_accepted(): void
    {
        $this->assertSame('/login', SlimConfig::fromArray(['ente' => 'Comune', 'login-url' => '/login'])->loginUrl);
        $this->assertSame('/search', CenterConfig::fromArray(['title' => 'Comune', 'search-url' => '/search'])->searchUrl);
        $this->assertSame('/privacy', LegalLinkConfig::fromArray(['url' => '/privacy', 'text' => 'Privacy'])->url);
    }

    private function assertInvalidArgument(callable $factory, string $field): void
    {
        try {
            $factory();
            $this->fail("Expected an invalid argument for field '{$field}'.");
        } catch (InvalidArgumentException $exception) {
            $this->assertStringContainsString($field, $exception->getMessage());
        }
    }
}
