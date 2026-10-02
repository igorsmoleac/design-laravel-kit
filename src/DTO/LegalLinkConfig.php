<?php

namespace IgorSmoleac\DesignLaravelKit\DTO;

use InvalidArgumentException;

final readonly class LegalLinkConfig
{
    public function __construct(
        public string $url,
        public string $text,
        public ?string $dataElement = null,
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
            url: ConfigValidator::requiredUrl($data, 'url'),
            text: ConfigValidator::requiredString($data, 'text'),
            dataElement: ConfigValidator::optionalString($data, 'dataElement'),
        );
    }
}
