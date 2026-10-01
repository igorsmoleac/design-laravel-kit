<?php

namespace IgorSmoleac\DesignLaravelKit\Components;

use Illuminate\Support\Str;
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
