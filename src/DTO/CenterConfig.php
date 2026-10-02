<?php

namespace IgorSmoleac\DesignLaravelKit\DTO;

use InvalidArgumentException;

final readonly class CenterConfig
{
    public function __construct(
        public string $title,
        public ?string $tagline = null,
        public ?string $logo = null,
        public ?string $logoAlt = null,
        public ?string $url = null,
        public ?string $searchUrl = null,
        public bool $small = false,
        public bool $light = false,
    ) {
        if (trim($this->title) === '') {
            throw new InvalidArgumentException("Field 'title' must be a non-empty string.");
        }

        ConfigValidator::assertUrl($this->logo, 'logo');
        ConfigValidator::assertUrl($this->url, 'url');
        ConfigValidator::assertUrl($this->searchUrl, 'searchUrl');
    }

    /** @param array<array-key, mixed> $data */
    public static function fromArray(array $data): self
    {
        $data = ConfigValidator::normalize($data);

        return new self(
            title: ConfigValidator::requiredString($data, 'title'),
            tagline: ConfigValidator::optionalString($data, 'tagline'),
            logo: ConfigValidator::optionalUrl($data, 'logo'),
            logoAlt: ConfigValidator::optionalString($data, 'logoAlt'),
            url: ConfigValidator::optionalUrl($data, 'url'),
            searchUrl: ConfigValidator::optionalUrl($data, 'searchUrl'),
            small: ConfigValidator::boolean($data, 'small'),
            light: ConfigValidator::boolean($data, 'light'),
        );
    }
}
