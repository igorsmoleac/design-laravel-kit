<?php

namespace IgorSmoleac\DesignLaravelKit\Tests\Unit\Components;

use IgorSmoleac\DesignLaravelKit\DesignLaravelKitServiceProvider;
use Orchestra\Testbench\TestCase;

class ModalTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [DesignLaravelKitServiceProvider::class];
    }

    public function test_renders_basic_modal(): void
    {
        $html = (string) $this->blade('<x-italia::modal title="Titolo">Contenuto</x-italia::modal>');

        $this->assertStringContainsString('modal fade', $html);
        $this->assertStringContainsString('modal-dialog', $html);
        $this->assertStringContainsString('modal-content', $html);
    }

    public function test_role_dialog_is_present(): void
    {
        $html = (string) $this->blade('<x-italia::modal title="T">C</x-italia::modal>');

        $this->assertStringContainsString('role="dialog"', $html);
    }

    public function test_modal_has_aria_hidden_true_initially(): void
    {
        $html = (string) $this->blade('<x-italia::modal title="T">C</x-italia::modal>');

        $this->assertStringContainsString('aria-hidden="true"', $html);
    }

    public function test_modal_does_not_have_static_aria_modal(): void
    {
        $html = (string) $this->blade('<x-italia::modal title="T">C</x-italia::modal>');

        $this->assertStringNotContainsString('aria-modal="true"', $html);
    }

    public function test_aria_labelledby_references_existing_title_id(): void
    {
        $html = (string) $this->blade('<x-italia::modal title="Titolo">C</x-italia::modal>');

        preg_match('/aria-labelledby="([^"]+)"/', $html, $labelledBy);
        preg_match('/id="([^"]+-title)"/', $html, $titleId);

        $this->assertNotEmpty($labelledBy);
        $this->assertSame($titleId[1], $labelledBy[1]);
    }

    public function test_modal_without_description_has_no_aria_describedby(): void
    {
        $html = (string) $this->blade('<x-italia::modal title="T">C</x-italia::modal>');

        $this->assertStringNotContainsString('aria-describedby=', $html);
        $this->assertStringNotContainsString('modal-description', $html);
    }

    public function test_modal_with_description_has_aria_describedby(): void
    {
        $html = (string) $this->blade('<x-italia::modal id="test" title="T" description="Descrizione">C</x-italia::modal>');

        $this->assertStringContainsString('aria-describedby="test-description"', $html);
        $this->assertStringContainsString('<p id="test-description">Descrizione</p>', $html);
    }

    public function test_modal_with_description_renders_description_text(): void
    {
        $html = (string) $this->blade('<x-italia::modal title="T" description="This operation cannot be undone.">C</x-italia::modal>');

        $this->assertStringContainsString('<p id="dlk-modal-', $html);
        $this->assertStringContainsString('>This operation cannot be undone.</p>', $html);
    }

    public function test_title_renders_in_modal_title(): void
    {
        $html = (string) $this->blade('<x-italia::modal title="Conferma azione">C</x-italia::modal>');

        $this->assertStringContainsString('<h2 class="modal-title h5"', $html);
        $this->assertStringContainsString('Conferma azione', $html);
    }

    public function test_tabindex_minus_one_on_modal(): void
    {
        $html = (string) $this->blade('<x-italia::modal title="T">C</x-italia::modal>');

        $this->assertStringContainsString('tabindex="-1"', $html);
    }

    public function test_dismissible_renders_close_button(): void
    {
        $html = (string) $this->blade('<x-italia::modal title="T">C</x-italia::modal>');

        $this->assertStringContainsString('btn-close', $html);
        $this->assertStringContainsString('data-bs-dismiss="modal"', $html);
        $this->assertStringContainsString('aria-label="Chiudi finestra modale"', $html);
    }

    public function test_not_dismissible_renders_no_close_button(): void
    {
        $html = (string) $this->blade('<x-italia::modal title="T" :dismissible="false">C</x-italia::modal>');

        $this->assertStringNotContainsString('btn-close', $html);
        $this->assertStringNotContainsString('data-bs-dismiss', $html);
    }

    public function test_centered_renders_dialog_class(): void
    {
        $html = (string) $this->blade('<x-italia::modal title="T" centered>C</x-italia::modal>');

        $this->assertStringContainsString('modal-dialog modal-dialog-centered', $html);
    }

    public function test_scrollable_renders_dialog_class(): void
    {
        $html = (string) $this->blade('<x-italia::modal title="T" scrollable>C</x-italia::modal>');

        $this->assertStringContainsString('modal-dialog modal-dialog-scrollable', $html);
    }

    public function test_size_renders_dialog_class(): void
    {
        $lg = (string) $this->blade('<x-italia::modal title="T" size="lg">C</x-italia::modal>');
        $sm = (string) $this->blade('<x-italia::modal title="T" size="sm">C</x-italia::modal>');
        $xl = (string) $this->blade('<x-italia::modal title="T" size="xl">C</x-italia::modal>');

        $this->assertStringContainsString('modal-dialog modal-lg', $lg);
        $this->assertStringContainsString('modal-dialog modal-sm', $sm);
        $this->assertStringContainsString('modal-dialog modal-xl', $xl);
    }

    public function test_static_renders_backdrop_and_keyboard_attributes(): void
    {
        $html = (string) $this->blade('<x-italia::modal title="T" static>C</x-italia::modal>');

        $this->assertStringContainsString('data-bs-backdrop="static"', $html);
        $this->assertStringContainsString('data-bs-keyboard="false"', $html);
    }

    public function test_modal_uses_explicit_id_when_provided(): void
    {
        $html = (string) $this->blade('<x-italia::modal id="my-modal" title="T">C</x-italia::modal>');

        $this->assertStringContainsString('id="my-modal"', $html);
        $this->assertStringContainsString('id="my-modal-title"', $html);
        $this->assertStringContainsString('id="my-modal-body"', $html);
        $this->assertStringContainsString('aria-labelledby="my-modal-title"', $html);
    }

    public function test_modal_generates_unique_id_when_not_provided(): void
    {
        $html = (string) $this->blade('<x-italia::modal title="A">C</x-italia::modal><x-italia::modal title="B">C</x-italia::modal>');

        preg_match_all('/id="(dlk-modal-\d+)"/', $html, $ids);

        $this->assertCount(2, $ids[1]);
        $this->assertNotSame($ids[1][0], $ids[1][1]);
    }

    public function test_slot_renders_in_modal_body(): void
    {
        $html = (string) $this->blade('<x-italia::modal title="T">Contenuto della modal.</x-italia::modal>');

        $this->assertStringContainsString('<div class="modal-body"', $html);
        $this->assertStringContainsString('Contenuto della modal.', $html);
    }

    public function test_footer_slot_renders_in_modal_footer(): void
    {
        $html = (string) $this->blade('<x-italia::modal title="T">C<x-slot:footer><button>Ok</button></x-slot:footer></x-italia::modal>');

        $this->assertStringContainsString('<div class="modal-footer">', $html);
        $this->assertStringContainsString('<button>Ok</button>', $html);
    }

    public function test_no_footer_slot_renders_no_modal_footer(): void
    {
        $html = (string) $this->blade('<x-italia::modal title="T">C</x-italia::modal>');

        $this->assertStringNotContainsString('modal-footer', $html);
    }

    public function test_dialog_has_document_role(): void
    {
        $html = (string) $this->blade('<x-italia::modal title="T">C</x-italia::modal>');

        $this->assertStringContainsString('modal-dialog" role="document"', $html);
    }

    public function test_custom_class_passed_through(): void
    {
        $html = (string) $this->blade('<x-italia::modal title="T" class="my-modal">C</x-italia::modal>');

        $this->assertStringContainsString('modal fade my-modal', $html);
    }
}
