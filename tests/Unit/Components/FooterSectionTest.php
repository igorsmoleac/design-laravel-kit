<?php

namespace IgorSmoleac\DesignLaravelKit\Tests\Unit\Components;

use IgorSmoleac\DesignLaravelKit\DesignLaravelKitServiceProvider;
use Orchestra\Testbench\TestCase;

class FooterSectionTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [DesignLaravelKitServiceProvider::class];
    }

    public function test_renders_without_custom_attributes(): void
    {
        $html = (string) $this->blade('<x-italia::footer-section title="Services"><li>Service</li></x-italia::footer-section>');

        $this->assertStringContainsString('class="col-lg-3 col-md-3 col-sm-6 pb-2"', $this->rootTag($html));
        $this->assertStringContainsString('Services', $html);
        $this->assertStringContainsString('<li>Service</li>', $html);
    }

    public function test_adds_custom_class_to_root_element(): void
    {
        $html = (string) $this->blade('<x-italia::footer-section title="Services" class="custom" />');

        $this->assertStringContainsString('class="custom"', $this->rootTag($html));
    }

    public function test_adds_data_attribute_to_root_element(): void
    {
        $html = (string) $this->blade('<x-italia::footer-section title="Services" data-analytics="x" />');

        $this->assertStringContainsString('data-analytics="x"', $this->rootTag($html));
    }

    public function test_adds_id_to_root_element(): void
    {
        $html = (string) $this->blade('<x-italia::footer-section title="Services" id="anchor" />');

        $this->assertStringContainsString('id="anchor"', $this->rootTag($html));
    }

    private function rootTag(string $html): string
    {
        return explode('>', trim($html), 2)[0];
    }
}
