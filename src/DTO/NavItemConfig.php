<?php

namespace IgorSmoleac\DesignLaravelKit\DTO;

use InvalidArgumentException;

final readonly class NavItemConfig
{
    public function __construct(
        public string $text,
        public string $url,
        public bool $active = false,
    ) {
        if (trim($this->text) === '') {
            throw new InvalidArgumentException("Field 'text' must be a non-empty string.");
        }

        if (trim($this->url) === '') {
            throw new InvalidArgumentException("Field 'url' must be a non-empty string.");
        }

        ConfigValidator::assertUrl($this->url, 'url');
    }

    /** @param array<array-key, mixed> $data */
    public static function fromArray(array $data): self
    {
        $data = ConfigValidator::normalize($data);

        return new self(
            text: ConfigValidator::requiredString($data, 'text'),
            url: ConfigValidator::requiredUrl($data, 'url'),
            active: ConfigValidator::boolean($data, 'active'),
        );
    }
}
