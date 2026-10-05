<?php

namespace IgorSmoleac\DesignLaravelKit\Tests\Unit\Components;

use IgorSmoleac\DesignLaravelKit\DesignLaravelKitServiceProvider;
use Orchestra\Testbench\TestCase;

class HeaderNavItemTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [DesignLaravelKitServiceProvider::class];
    }

    public function test_renders_without_custom_attributes(): void
    {
        $html = (string) $this->blade('<x-italia::header-nav-item text="Home" url="/home" />');

        $this->assertStringContainsString('class="nav-item"', $this->rootTag($html));
        $this->assertStringContainsString('href="/home"', $html);
    }

    public function test_adds_custom_class_to_root_element(): void
    {
        $html = (string) $this->blade('<x-italia::header-nav-item text="Home" url="/home" class="custom" />');

        $this->assertStringContainsString('class="custom"', $this->rootTag($html));
    }

    public function test_adds_data_attribute_to_root_element(): void
    {
        $html = (string) $this->blade('<x-italia::header-nav-item text="Home" url="/home" data-analytics="x" />');

        $this->assertStringContainsString('data-analytics="x"', $this->rootTag($html));
    }

    public function test_adds_id_to_root_element(): void
    {
        $html = (string) $this->blade('<x-italia::header-nav-item text="Home" url="/home" id="anchor" />');

        $this->assertStringContainsString('id="anchor"', $this->rootTag($html));
    }

    public function test_adds_attributes_to_dropdown_root_element(): void
    {
        $html = (string) $this->blade('<x-italia::header-nav-item text="Services" dropdown class="custom"><li>Service</li></x-italia::header-nav-item>');

        $this->assertStringContainsString('class="nav-item dropdown"', $html);
        $this->assertStringContainsString('class="custom"', $this->rootTag($html));
    }

    private function rootTag(string $html): string
    {
        return explode('>', trim($html), 2)[0];
    }
}
