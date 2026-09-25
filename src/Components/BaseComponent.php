<?php

namespace IgorSmoleac\DesignLaravelKit\Components;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use Illuminate\Support\ViewErrorBag;
use Illuminate\View\Component;

abstract class BaseComponent extends Component
{
    /**
     * Cached unique identifier, resolved once per component instance.
     */
    protected ?string $uid = null;

    /**
     * Prefix for generated HTML ids, configurable via design-laravel-kit.id_prefix.
     */
    public static function idPrefix(): string
    {
        return (string) config('design-laravel-kit.id_prefix', 'dlk');
    }

    /* ------------------------------------------------------------------
     |  Unique identifiers
     | ----------------------------------------------------------------- */

    /**
     * Unique id for the root element: explicit attribute wins, otherwise
     * derived from the field name, otherwise generated randomly.
     */
    public function id(): string
    {
        if ($this->uid !== null) {
            return $this->uid;
        }

        $explicit = $this->attributes?->get('id');

        if ($explicit !== null && $explicit !== '') {
            return $this->uid = (string) $explicit;
        }

        return $this->uid = $this->buildBaseId();
    }

    /**
     * spl_object_id() guarantees unique ids per instance within a single
     * render — duplicate ids violate WCAG 2.1 AA (Legge Stanca) and break
     * label/for binding when the same field name appears twice on a page.
     */
    protected function buildBaseId(): string
    {
        $seed = $this->idSeed();

        if ($seed !== null && $seed !== '') {
            return static::idPrefix() . '-' . $this->slugifyId($seed) . '-' . spl_object_id($this);
        }

        return static::idPrefix() . '-' . Str::kebab(class_basename(static::class)) . '-' . spl_object_id($this);
    }

    /**
     * Id of the element that carries the error message.
     */
    public function errorId(): string
    {
        return $this->id() . '-error';
    }

    /**
     * Id of the element that carries the hint text.
     */
    public function hintId(): string
    {
        return $this->id() . '-hint';
    }

    /**
     * Source value used to derive the unique id (usually the field name).
     * Form components should override this to return their name property.
     */
    protected function idSeed(): ?string
    {
        return $this->attributes?->get('name');
    }

    protected function slugifyId(string $seed): string
    {
        return trim(str_replace(['[', ']'], '-', $seed), '-');
    }

    /* ------------------------------------------------------------------
     |  Validation errors
     | ----------------------------------------------------------------- */

    public function hasError(): bool
    {
        return ($field = $this->errorField()) !== null
            && $this->errors()->has($field);
    }

    /**
     * First validation message for the field, in dot notation lookup.
     */
    public function errorMessage(): ?string
    {
        $field = $this->errorField();

        if ($field === null) {
            return null;
        }

        return $this->errors()->first($field) ?: null;
    }

    /**
     * Shared error bag, resolved from the view factory (session middleware).
     */
    protected function errors(): ViewErrorBag
    {
        $shared = View::shared('errors');

        return $shared instanceof ViewErrorBag ? $shared : new ViewErrorBag;
    }

    /**
     * Field name in dot notation for $errors lookup: user[email] → user.email.
     * Read from the name attribute directly, independent of idSeed(), so
     * components overriding idSeed() (e.g. checkbox groups) keep error binding.
     */
    protected function errorField(): ?string
    {
        $name = $this->attributes?->get('name');

        if ($name === null || $name === '') {
            return null;
        }

        return $this->toDotNotation((string) $name);
    }

    protected function toDotNotation(string $name): string
    {
        $name = preg_replace('/\[\]$/', '', $name);
        $name = str_replace(['[', ']'], ['.', ''], $name);

        return rtrim($name, '.');
    }

    /* ------------------------------------------------------------------
     |  ARIA attributes
     | ----------------------------------------------------------------- */

    /**
     * ARIA attributes to merge into the root element:
     * {{ $attributes->merge($ariaAttributes) }}
     */
    public function ariaAttributes(): array
    {
        return array_filter([
            'aria-invalid' => $this->hasError() ? 'true' : null,
            'aria-required' => $this->isRequired() ? 'true' : null,
            'aria-describedby' => $this->describedBy() ?: null,
        ]);
    }

    /**
     * Space-separated list of ids referenced by aria-describedby.
     */
    public function describedBy(): string
    {
        return collect([
            $this->hasHint() ? $this->hintId() : null,
            $this->hasError() ? $this->errorId() : null,
        ])->filter()->implode(' ');
    }

    /**
     * Whether the component renders a hint element.
     * Components with hint support should override this.
     */
    protected function hasHint(): bool
    {
        return false;
    }

    protected function isRequired(): bool
    {
        return (bool) ($this->attributes?->has('required')
            || $this->attributes?->get('aria-required') === 'true');
    }
}
