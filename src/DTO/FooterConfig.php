<?php

namespace IgorSmoleac\DesignLaravelKit\DTO;

use InvalidArgumentException;

final readonly class FooterConfig
{
    public ?string $title;

    /**
     * @param  list<LegalLinkConfig>  $legalLinks
     * @param  list<array<string, mixed>>  $sections
     * @param  list<array<string, mixed>>  $contacts
     * @param  list<SocialLinkConfig>  $social
     */
    public function __construct(
        ?string $title = null,
        public ?string $subtitle = null,
        public ?string $logo = null,
        public ?string $logoAlt = null,
        public ?string $url = null,
        public array $legalLinks = [],
        public array $sections = [],
        public array $contacts = [],
        public array $social = [],
        public ?string $copyright = null,
        public bool $light = false,
    ) {
        $this->title = $title === '' ? null : $title;

        if ($this->logo !== null && $this->logo !== '') {
            ConfigValidator::logoUrl($this->logo, 'logo');
        }

        ConfigValidator::assertUrl($this->url, 'url');

        foreach ($this->legalLinks as $legalLink) {
            if (! $legalLink instanceof LegalLinkConfig) {
                throw new InvalidArgumentException("Field 'legalLinks' must contain LegalLinkConfig values.");
            }
        }

        foreach ($this->social as $socialLink) {
            if (! $socialLink instanceof SocialLinkConfig) {
                throw new InvalidArgumentException("Field 'social' must contain SocialLinkConfig values.");
            }
        }
    }

    /** @param array<array-key, mixed> $data */
    public static function fromArray(array $data): self
    {
        $data = ConfigValidator::normalize($data);

        return new self(
            title: ConfigValidator::optionalString($data, 'title'),
            subtitle: ConfigValidator::optionalString($data, 'subtitle'),
            logo: ConfigValidator::optionalString($data, 'logo'),
            logoAlt: ConfigValidator::optionalString($data, 'logoAlt'),
            url: ConfigValidator::optionalUrl($data, 'url'),
            legalLinks: self::legalLinks(ConfigValidator::list($data, 'legalLinks')),
            sections: self::arrayList(ConfigValidator::list($data, 'sections'), 'sections'),
            contacts: self::arrayList(ConfigValidator::list($data, 'contacts'), 'contacts'),
            social: self::socialLinks(ConfigValidator::list($data, 'social')),
            copyright: ConfigValidator::optionalString($data, 'copyright'),
            light: ConfigValidator::boolean($data, 'light'),
        );
    }

    /** @param list<mixed> $items
     * @return list<LegalLinkConfig>
     */
    private static function legalLinks(array $items): array
    {
        return array_map(static function (mixed $item): LegalLinkConfig {
            if ($item instanceof LegalLinkConfig) {
                return $item;
            }

            if (! is_array($item)) {
                throw new InvalidArgumentException("Field 'legalLinks' must contain LegalLinkConfig values or arrays.");
            }

            return LegalLinkConfig::fromArray($item);
        }, $items);
    }

    /** @param list<mixed> $items
     * @return list<array<string, mixed>>
     */
    private static function arrayList(array $items, string $field): array
    {
        foreach ($items as $index => $item) {
            if (! is_array($item)) {
                throw new InvalidArgumentException("Field '{$field}' must contain arrays.");
            }

            if ($field !== 'sections') {
                continue;
            }

            self::validateNestedUrl($item['url'] ?? null, "sections.{$index}.url");

            $links = $item['links'] ?? [];

            if (! is_array($links) || ! array_is_list($links)) {
                throw new InvalidArgumentException("Field 'sections.{$index}.links' must be a list.");
            }

            foreach ($links as $linkIndex => $link) {
                if (! is_array($link)) {
                    throw new InvalidArgumentException("Field 'sections.{$index}.links.{$linkIndex}' must be an array.");
                }

                self::validateNestedUrl($link['url'] ?? null, "sections.{$index}.links.{$linkIndex}.url");
            }
        }

        return $items;
    }

    private static function validateNestedUrl(mixed $url, string $field): void
    {
        if ($url === null || $url === '') {
            return;
        }

        if (! is_string($url)) {
            throw new InvalidArgumentException("Field '{$field}' must be a string or null.");
        }

        ConfigValidator::assertUrl($url, $field);
    }

    /** @param list<mixed> $items
     * @return list<SocialLinkConfig>
     */
    private static function socialLinks(array $items): array
    {
        return array_map(static function (mixed $item): SocialLinkConfig {
            if ($item instanceof SocialLinkConfig) {
                return $item;
            }

            if (! is_array($item)) {
                throw new InvalidArgumentException("Field 'social' must contain SocialLinkConfig values or arrays.");
            }

            return SocialLinkConfig::fromArray($item);
        }, $items);
    }
}
