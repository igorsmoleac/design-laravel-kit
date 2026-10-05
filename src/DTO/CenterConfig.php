<?php

namespace IgorSmoleac\DesignLaravelKit\DTO;

final readonly class CenterConfig
{
    public ?string $title;

    public function __construct(
        ?string $title = null,
        public ?string $tagline = null,
        public ?string $logo = null,
        public ?string $logoAlt = null,
        public ?string $url = null,
        public ?string $searchUrl = null,
        public bool $small = false,
        public bool $light = false,
    ) {
        $this->title = $title === '' ? null : $title;

        if ($this->logo !== null && $this->logo !== '') {
            ConfigValidator::logoUrl($this->logo, 'logo');
        }

        ConfigValidator::assertUrl($this->url, 'url');
        ConfigValidator::assertUrl($this->searchUrl, 'searchUrl');
    }

    /** @param array<array-key, mixed> $data */
    public static function fromArray(array $data): self
    {
        $data = ConfigValidator::normalize($data);

        return new self(
            title: ConfigValidator::optionalString($data, 'title'),
            tagline: ConfigValidator::optionalString($data, 'tagline'),
            logo: ConfigValidator::optionalString($data, 'logo'),
            logoAlt: ConfigValidator::optionalString($data, 'logoAlt'),
            url: ConfigValidator::optionalUrl($data, 'url'),
            searchUrl: ConfigValidator::optionalUrl($data, 'searchUrl'),
            small: ConfigValidator::boolean($data, 'small'),
            light: ConfigValidator::boolean($data, 'light'),
        );
    }
}
