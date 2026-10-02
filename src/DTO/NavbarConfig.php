<?php

namespace IgorSmoleac\DesignLaravelKit\DTO;

use InvalidArgumentException;

final readonly class NavbarConfig
{
    /**
     * @param  list<NavItemConfig>  $items
     */
    public function __construct(
        public array $items = [],
        public bool $light = false,
        public bool $sticky = false,
    ) {
        if (! array_is_list($this->items)) {
            throw new InvalidArgumentException("Field 'items' must be a list of NavItemConfig values.");
        }

        foreach ($this->items as $item) {
            if (! $item instanceof NavItemConfig) {
                throw new InvalidArgumentException("Field 'items' must contain only NavItemConfig values.");
            }
        }
    }

    /** @param array<array-key, mixed> $data */
    public static function fromArray(array $data): self
    {
        $data = ConfigValidator::normalize($data);
        $items = array_map(
            static function (mixed $item): NavItemConfig {
                if ($item instanceof NavItemConfig) {
                    return $item;
                }

                if (! is_array($item)) {
                    throw new InvalidArgumentException("Field 'items' must contain NavItemConfig values or arrays.");
                }

                return NavItemConfig::fromArray($item);
            },
            ConfigValidator::list($data, 'items'),
        );

        return new self(
            items: $items,
            light: ConfigValidator::boolean($data, 'light'),
            sticky: ConfigValidator::boolean($data, 'sticky'),
        );
    }
}
