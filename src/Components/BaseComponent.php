<?php

namespace IgorSmoleac\DesignLaravelKit\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Illuminate\View\Component;
use Illuminate\View\ComponentSlot;

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

    protected function componentView(string $viewName): View
    {
        return view()->make($viewName);
    }

    /** @param list<string> $names */
    protected function rejectArrayAttributes(array $names): void
    {
        foreach ($names as $name) {
            if (is_array($this->attributes?->get($name))) {
                throw new \InvalidArgumentException(
                    "The '{$name}' array attribute is no longer supported; use named Blade slots."
                );
            }
        }
    }

    /** @param array<string, mixed> $data
     * @return array<string, mixed>|null
     */
    protected function slotAttributes(array $data, string $name): ?array
    {
        $slot = $data[$name] ?? null;

        if (! $slot instanceof ComponentSlot) {
            return null;
        }

        $attributes = $slot->attributes->all();

        foreach ($attributes as $attribute => $value) {
            if (is_array($value)) {
                throw new \InvalidArgumentException(
                    "Slot '{$name}' attribute '{$attribute}' must be a named scalar value, not an array."
                );
            }
        }

        return $attributes;
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
     * Builds a unique ID for this component instance.
     *
     * `spl_object_id()` guarantees uniqueness within a single render. IDs are not
     * stable across requests and must not be cached separately from the parent
     * view: PHP may reuse object IDs after garbage collection, causing collisions
     * in concurrent caches.
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
}
