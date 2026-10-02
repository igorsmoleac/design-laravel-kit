<?php

namespace IgorSmoleac\DesignLaravelKit\DTO;

use Illuminate\Support\Str;
use InvalidArgumentException;

final class ConfigValidator
{
    /** @param array<array-key, mixed> $data
     * @return array<string, mixed>
     */
    public static function normalize(array $data): array
    {
        $normalized = [];

        foreach ($data as $key => $value) {
            $normalized[Str::camel((string) $key)] = $value;
        }

        return $normalized;
    }

    /** @param array<string, mixed> $data */
    public static function requiredString(array $data, string $field): string
    {
        $value = $data[$field] ?? null;

        if (! is_string($value) || trim($value) === '') {
            throw new InvalidArgumentException("Field '{$field}' must be a non-empty string.");
        }

        return $value;
    }

    /** @param array<string, mixed> $data */
    public static function optionalString(array $data, string $field, ?string $default = null): ?string
    {
        $value = $data[$field] ?? $default;

        if ($value !== null && ! is_string($value)) {
            throw new InvalidArgumentException("Field '{$field}' must be a string or null.");
        }

        return $value;
    }

    /** @param array<string, mixed> $data */
    public static function boolean(array $data, string $field, bool $default = false): bool
    {
        $value = $data[$field] ?? $default;

        if (is_bool($value)) {
            return $value;
        }

        $validated = filter_var($value, FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE);

        if ($validated === null) {
            throw new InvalidArgumentException("Field '{$field}' must be a boolean.");
        }

        return $validated;
    }

    /** @param array<string, mixed> $data
     * @return list<mixed>
     */
    public static function list(array $data, string $field): array
    {
        $value = $data[$field] ?? [];

        if (! is_array($value) || ! array_is_list($value)) {
            throw new InvalidArgumentException("Field '{$field}' must be a list.");
        }

        return $value;
    }

    public static function assertUrl(?string $url, string $field): void
    {
        if ($url === null || $url === '') {
            return;
        }

        if (str_starts_with($url, '/') && ! str_starts_with($url, '//')) {
            if (preg_match('/[\s<>"\']/', $url) === 1) {
                throw new InvalidArgumentException("Field '{$field}' must be a valid HTTP(S) URL or root-relative path.");
            }

            return;
        }

        if (! preg_match('#^https?://#i', $url) || filter_var($url, FILTER_VALIDATE_URL) === false) {
            throw new InvalidArgumentException("Field '{$field}' must be a valid HTTP(S) URL or root-relative path.");
        }
    }

    /** @param array<string, mixed> $data */
    public static function optionalUrl(array $data, string $field): ?string
    {
        $url = self::optionalString($data, $field);
        self::assertUrl($url, $field);

        return $url;
    }

    /** @param array<string, mixed> $data */
    public static function requiredUrl(array $data, string $field): string
    {
        $url = self::requiredString($data, $field);
        self::assertUrl($url, $field);

        return $url;
    }
}
