<?php

namespace IgorSmoleac\DesignLaravelKit\Tests\Unit\Components;

use IgorSmoleac\DesignLaravelKit\Components\Input;
use Illuminate\Support\Facades\View;
use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;
use Orchestra\Testbench\TestCase;

class BaseFormComponentTest extends TestCase
{
    public function test_has_error_returns_false_without_shared_errors(): void
    {
        View::share('errors', null);

        $this->assertFalse((new Input('email'))->hasError());
    }

    public function test_error_message_returns_null_without_error(): void
    {
        View::share('errors', new ViewErrorBag);

        $this->assertNull((new Input('email'))->errorMessage());
    }

    public function test_error_field_converts_brackets_to_dots(): void
    {
        $this->shareErrors(['user.email' => 'The email is invalid.']);
        $component = new Input('user[email]');

        $this->assertTrue($component->hasError());
        $this->assertSame('The email is invalid.', $component->errorMessage());
    }

    public function test_error_field_converts_array_syntax(): void
    {
        $this->shareErrors(['roles' => 'The roles field is required.']);
        $component = new Input('roles[]');

        $this->assertTrue($component->hasError());
        $this->assertSame('The roles field is required.', $component->errorMessage());
    }

    public function test_id_seed_returns_name(): void
    {
        $component = new Input('user[email]');
        $id = $component->id();

        $this->assertMatchesRegularExpression('/^dlk-user-email-\d+$/', $id);
        $this->assertSame($id . '-error', $component->errorId());
        $this->assertSame($id . '-hint', $component->hintId());
    }

    public function test_described_by_includes_hint_and_error(): void
    {
        $this->shareErrors(['email' => 'The email field is required.']);
        $component = new Input('email', hint: 'Email hint');

        $this->assertSame($component->hintId() . ' ' . $component->errorId(), $component->describedBy());
    }

    private function shareErrors(array $messages): void
    {
        View::share('errors', (new ViewErrorBag)->put('default', new MessageBag($messages)));
    }
}
