<?php

namespace IgorSmoleac\DesignLaravelKit\Tests\Unit\Components;

use IgorSmoleac\DesignLaravelKit\DesignLaravelKitServiceProvider;
use Orchestra\Testbench\TestCase;

class SelectOptionTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [DesignLaravelKitServiceProvider::class];
    }

    public function test_renders_without_selected_when_no_old_input(): void
    {
        $html = (string) $this->blade('<x-italia::select name="city"><x-italia::select-option value="mi">Milano</x-italia::select-option></x-italia::select>');

        $this->assertStringContainsString('<option value="mi">Milano</option>', $html);
        $this->assertStringNotContainsString('selected', $html);
    }

    public function test_renders_selected_when_old_input_matches_value(): void
    {
        $this->withSession(['_old_input' => ['city' => 'mi']]);

        $html = (string) $this->blade('<x-italia::select name="city"><x-italia::select-option value="mi">Milano</x-italia::select-option><x-italia::select-option value="na">Napoli</x-italia::select-option></x-italia::select>');

        $this->assertStringContainsString('<option value="mi" selected>Milano</option>', $html);
        $this->assertStringNotContainsString('<option value="na" selected', $html);
    }

    public function test_renders_outside_select_without_selected(): void
    {
        $html = (string) $this->blade('<x-italia::select-option value="mi">Milano</x-italia::select-option>');

        $this->assertStringContainsString('<option value="mi">Milano</option>', $html);
        $this->assertStringNotContainsString('selected', $html);
    }

    public function test_label_param_renders_instead_of_slot(): void
    {
        $html = (string) $this->blade('<x-italia::select name="city"><x-italia::select-option value="mi" label="Milano" /></x-italia::select>');

        $this->assertStringContainsString('<option value="mi">Milano</option>', $html);
    }
}
