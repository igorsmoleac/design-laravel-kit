<?php

namespace IgorSmoleac\DesignLaravelKit\DTO;

use InvalidArgumentException;

final readonly class SlimConfig
{
    public function __construct(
        public string $ente,
        public ?string $enteUrl = null,
        public ?string $loginUrl = null,
        public ?string $loginLabel = null,
        public bool $light = false,
        public bool $sticky = false,
    ) {
        if (trim($this->ente) === '') {
            throw new InvalidArgumentException("Field 'ente' must be a non-empty string.");
        }

        if ($this->loginLabel !== null && trim($this->loginLabel) === '') {
            throw new InvalidArgumentException("Field 'loginLabel' must be a non-empty string.");
        }

        ConfigValidator::assertUrl($this->enteUrl, 'enteUrl');
        ConfigValidator::assertUrl($this->loginUrl, 'loginUrl');
    }

    /** @param array<array-key, mixed> $data */
    public static function fromArray(array $data): self
    {
        $data = ConfigValidator::normalize($data);

        return new self(
            ente: ConfigValidator::requiredString($data, 'ente'),
            enteUrl: ConfigValidator::optionalUrl($data, 'enteUrl'),
            loginUrl: ConfigValidator::optionalUrl($data, 'loginUrl'),
            loginLabel: ConfigValidator::optionalString($data, 'loginLabel'),
            light: ConfigValidator::boolean($data, 'light'),
            sticky: ConfigValidator::boolean($data, 'sticky'),
        );
    }
}
