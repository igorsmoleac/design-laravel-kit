<?php

namespace IgorSmoleac\DesignLaravelKit\DTO;

use InvalidArgumentException;

final readonly class SocialLinkConfig
{
    public function __construct(
        public string $url,
        public string $label,
        public string $icon = 'it-link',
    ) {
        if (trim($this->label) === '') {
            throw new InvalidArgumentException("Field 'label' must be a non-empty string.");
        }

        if (trim($this->icon) === '') {
            throw new InvalidArgumentException("Field 'icon' must be a non-empty string.");
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
            url: ConfigValidator::requiredUrl($data, 'url'),
            label: ConfigValidator::requiredString($data, 'label'),
            icon: ConfigValidator::optionalString($data, 'icon', 'it-link') ?? 'it-link',
        );
    }
}
