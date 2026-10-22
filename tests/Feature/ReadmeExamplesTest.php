<?php

namespace IgorSmoleac\DesignLaravelKit\Tests\Feature;

use IgorSmoleac\DesignLaravelKit\DesignLaravelKitServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\ViewException;
use Orchestra\Testbench\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class ReadmeExamplesTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [DesignLaravelKitServiceProvider::class];
    }

    /**
     * @return array<string, array{0: string, 1: string}>
     */
    public static function provideBladeExamples(): array
    {
        $readme = file_get_contents(dirname(__DIR__, 2) . '/README.md');

        if ($readme === false) {
            self::fail('README.md not found next to tests/.');
        }

        preg_match_all('/^```blade\s*\n(.*?)^```/ms', $readme, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);

        if ($matches === []) {
            self::fail('No ```blade examples found in README.md.');
        }

        $cases = [];

        foreach ($matches as $index => $match) {
            $line = substr_count(substr($readme, 0, $match[0][1]), "\n") + 1;
            $label = 'blade example #' . ($index + 1) . ' (README.md line ' . $line . ')';
            $cases[$label] = [$match[1][0], $label];
        }

        return $cases;
    }

    #[DataProvider('provideBladeExamples')]
    public function test_readme_blade_example_renders(string $code, string $label): void
    {
        try {
            $html = Blade::render($this->prepare($code));
        } catch (ViewException $exception) {
            if (str_contains($exception->getMessage(), 'Undefined variable')) {
                $this->markTestSkipped($label . ': requires application context (' . $exception->getMessage() . ')');
            }

            throw $exception;
        }

        $this->assertNotSame('', trim($html), $label . ' rendered an empty output.');
    }

    private function prepare(string $code): string
    {
        return preg_replace('/\{\{\s*route\([^)]*\)\s*\}\}/', '{{ \'\' }}', $code) ?? $code;
    }
}
