<?php

namespace IgorSmoleac\DesignLaravelKit\DTO;

use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Log;
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
        if ($url === null || self::isValidUrl($url)) {
            return;
        }

        if (self::strictUrls()) {
            self::throwInvalidUrl($url, $field);
        }

        Log::warning("Invalid URL in component field '{$field}': '" . self::displayUrl($url) . "' is not a valid link and is rendered as-is.");
    }

    /**
     * True when URLs must throw on invalid values; false for the lenient
     * mode that logs a warning and renders '#' instead. Defaults to strict
     * when no application config is available.
     */
    public static function strictUrls(): bool
    {
        $app = Facade::getFacadeApplication();

        $strict = $app !== null && $app->bound('config')
            ? config('design-laravel-kit.strict_urls')
            : null;

        return $strict ?? true;
    }

    private static function isValidUrl(?string $url): bool
    {
        if ($url === null) {
            return true;
        }

        $normalizedUrl = ltrim($url, " \t");
        $hasScheme = preg_match('/^([a-z][a-z0-9+.-]*):/i', $normalizedUrl, $schemeMatch) === 1;
        $scheme = $hasScheme ? strtolower($schemeMatch[1]) : null;

        if ($hasScheme && ! in_array($scheme, ['http', 'https', 'mailto', 'tel'], true)) {
            return false;
        }

        if ($url === '' || preg_match('/[\x00-\x20\x7F<>"\'\\\\]/', $url) === 1) {
            return false;
        }

        if ($url[0] === '#') {
            return true;
        }

        if ($scheme === 'mailto' || $scheme === 'tel') {
            return substr($normalizedUrl, strlen($schemeMatch[0])) !== '';
        }

        $parsedUrl = parse_url($url);

        if ($scheme === 'http' || $scheme === 'https') {
            return is_array($parsedUrl) && ! empty($parsedUrl['host']);
        }

        return ! str_starts_with($url, '//') && is_array($parsedUrl) && ! empty($parsedUrl['path']);
    }

    /**
     * Resolves a URL for rendering: valid URLs pass through, invalid ones
     * throw in strict mode or are replaced with '#' (with a logged warning)
     * in lenient mode.
     */
    private static function resolveUrl(?string $url, string $field): ?string
    {
        if ($url === null || self::isValidUrl($url)) {
            return $url;
        }

        if (self::strictUrls()) {
            self::throwInvalidUrl($url, $field);
        }

        Log::warning("Invalid URL in component field '{$field}': '" . self::displayUrl($url) . "' is not a valid link and is replaced with '#'.");

        return '#';
    }

    public static function logoUrl(string $value, string $field): string
    {
        if ($value === '') {
            throw new InvalidArgumentException("Field '{$field}' must be a valid HTTP(S) URL, root-relative path, or data:image/* URI.");
        }

        if (preg_match('/^data:image\/[a-z0-9][a-z0-9!#$&^_.+-]*(?:;[a-z0-9!#$&^_.+-]+(?:=[^;,]*)?)*,/i', $value) === 1) {
            return $value;
        }

        return self::resolveUrl($value, $field) ?? $value;
    }

    /** @param array<string, mixed> $data */
    public static function optionalUrl(array $data, string $field): ?string
    {
        return self::resolveUrl(self::optionalString($data, $field), $field);
    }

    /** @param array<string, mixed> $data */
    public static function requiredUrl(array $data, string $field): string
    {
        $url = self::optionalString($data, $field);

        if ($url === null) {
            return self::requiredString($data, $field);
        }

        return self::resolveUrl($url, $field) ?? $url;
    }

    public static function cspNonce(?string $explicit, ?string $configured): ?string
    {
        $nonce = $explicit !== null && trim($explicit) !== '' ? $explicit : $configured;

        if ($nonce === null || trim($nonce) === '') {
            return null;
        }

        if (preg_match('/^[A-Za-z0-9+\/=_-]+$/', $nonce) !== 1) {
            throw new InvalidArgumentException("Field 'csp_nonce' value '{$nonce}' may contain only base64-like characters (A-Z, a-z, 0-9, +, /, =, _, -) without quotes or spaces.");
        }

        return $nonce;
    }

    private static function throwInvalidUrl(string $url, string $field): never
    {
        throw new InvalidArgumentException("Field '{$field}' value '" . self::displayUrl($url) . "' is not a valid link. Allowed: http(s), mailto:, tel:, #fragment, root-relative, relative path.");
    }

    private static function displayUrl(string $url): string
    {
        return Str::length($url) > 80 ? Str::substr($url, 0, 77) . '...' : $url;
    }
}
