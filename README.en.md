# Design Laravel Kit

[![License: BSD-3-Clause](https://img.shields.io/badge/License-BSD%203--Clause-blue.svg)](https://opensource.org/licenses/BSD-3-Clause)
[![Laravel 12|13](https://img.shields.io/badge/Laravel-12%20%7C%2013-red.svg)](https://laravel.com)
[![PHP 8.3+](https://img.shields.io/badge/PHP-8.3%2B-blue.svg)](https://php.net)
[![Tests](https://github.com/igorsmoleac/design-laravel-kit/actions/workflows/tests.yml/badge.svg)](https://github.com/igorsmoleac/design-laravel-kit/actions)

[🇮🇹 Italiano](README.md) | 🇬🇧 **English**

Modern Bootstrap Italia integration for Laravel. Blade components, Vite, accessibility.

> Package under development.

<!-- TODO: screenshots -->

## What it does

A Composer package for building Italian Public Administration websites
and digital services with Laravel. It provides ready-to-use Blade
components compliant with the Italian PA design guidelines.

**Who it's for:** digital agencies, system integrators and developers
working on public sector projects — municipalities, schools, health
authorities, ministries. The goal is to reduce the time to build
institutional templates from days to a few hours.

**What it includes:** three-tier header (slim, center, navbar),
AgID-compliant footer, forms with automatic validation error binding
and ARIA attributes, complete layout for an institutional page.

## Requirements

- PHP **8.3** or higher
- Laravel **12** or **13**
- Node.js 20+ (only to build the package assets — not required at runtime)

## Installation

```bash
composer require igorsmoleac/design-laravel-kit
php artisan design-laravel-kit:install
```

The `install` command publishes the configuration and the assets (CSS, JS,
fonts, SVG sprite) to `public/vendor/design-laravel-kit/`.

Add the directives to your Blade layout:

```blade
<head>
    @designLaravelKitStyles
</head>
<body>
    ...
    @designLaravelKitScripts
</body>
```

## Quickstart

```blade
<x-italia::layout
    title="Comune di Roma — Portale istituzionale"
    :slim="[
        'ente' => 'Comune di Roma',
        'enteUrl' => 'https://www.comune.roma.it',
        'languages' => [
            ['code' => 'it', 'label' => 'ITA', 'active' => true],
            ['code' => 'en', 'label' => 'ENG'],
        ],
        'loginUrl' => '/login',
    ]"
    :center="[
        'title' => 'Comune di Roma',
        'tagline' => 'Portale istituzionale',
        'url' => '/',
        'searchUrl' => '/search',
    ]"
    :navbar="[
        'items' => [
            ['text' => 'Home', 'url' => '/', 'active' => true],
            ['text' => 'Amministrazione', 'url' => '/amministrazione'],
            ['text' => 'Servizi', 'url' => '/servizi'],
        ],
    ]"
    :footer="[
        'title' => 'Comune di Roma',
        'legalLinks' => [
            ['url' => '/privacy', 'text' => 'Privacy policy', 'dataElement' => 'privacy-policy-link'],
            ['url' => '/accessibilita', 'text' => 'Dichiarazione di accessibilità', 'dataElement' => 'accessibility-link'],
        ],
    ]"
>
    <h1>Benvenuto</h1>
    <p>Contenuto della pagina.</p>
</x-italia::layout>
```

This produces a complete page compliant with the PA guidelines:
three-tier header, footer with mandatory legal links,
automatic skip-to-content link, ARIA attributes.

## Components

All components use the `<x-italia::` prefix, configurable via the
`component_prefix` option in `config/design-laravel-kit.php`.

### Layout

Complete template for an institutional page: header, main, footer
and skip-to-content link.

```blade
<x-italia::layout title="..." :slim="[...]" :center="[...]" :navbar="[...]" :footer="[...]">
    <h1>Content</h1>
</x-italia::layout>
```

### Header

Three-tier header: slim (institution and languages), center (brand and search),
navbar (navigation menu).

```blade
<x-italia::header
    :slim="['ente' => 'Institution name']"
    :center="['title' => 'Site name', 'searchUrl' => '/search']"
    :navbar="['items' => [['text' => 'Home', 'url' => '/']]]"
/>
```

### Footer

AgID-compliant footer with sections, contacts and mandatory legal links.

```blade
<x-italia::footer
    title="Institution name"
    :sections="[...]"
    :contacts="[...]"
    :legal-links="[
        ['url' => '/privacy', 'text' => 'Privacy policy', 'dataElement' => 'privacy-policy-link'],
        ['url' => '/accessibilita', 'text' => 'Accessibility statement', 'dataElement' => 'accessibility-link'],
    ]"
/>
```

### Icon

Icon from the Bootstrap Italia SVG sprite.

```blade
<x-italia::icon name="it-search" />
<x-italia::icon name="it-close" size="lg" />
<x-italia::icon name="it-user" label="User profile" />
```

### Button

Button with variants, sizes and states (loading, disabled, link).

```blade
<x-italia::button>Primary</x-italia::button>
<x-italia::button variant="secondary" size="lg">Secondary</x-italia::button>
<x-italia::button href="/login" variant="primary">Sign in</x-italia::button>
<x-italia::button loading>Loading…</x-italia::button>
```

### Input

Input field with automatic validation error binding.

```blade
<x-italia::input name="email" type="email" label="Email" required />
<x-italia::input name="name" label="Full name" hint="Your full name" />
```

### Select

Dropdown list with support for optgroups, `multiple` and placeholder.

```blade
<x-italia::select name="province" label="Province" :options="[
    'rm' => 'Roma',
    'mi' => 'Milano',
    'na' => 'Napoli',
]" placeholder="Select…" />
```

### Textarea

Multi-line field with automatic validation error binding.

```blade
<x-italia::textarea name="message" label="Message" :rows="5" />
```

## Design system

The package is based on **Bootstrap Italia 2.18.3**, the official stable
version of the design library for the Italian Public Administration.

**Why not v3?** Version 3.0 is currently in beta and introduces substantial
changes to the markup and CSS tokens. v2.18.3 is stable, documented and
used in production by PA websites.

**Why not design-tokens-italia separately?** Bootstrap Italia already
includes the official design tokens in its CSS. Adding them separately
would create duplication and conflicts.

## Performance

The package publishes the full Bootstrap Italia bundle (~1 MB, ~250-300 KB
gzip). This is the price of zero-config: no Vite configuration required.

If you need a smaller bundle, you can import only the components you need
in your own Vite project and skip `@designLaravelKitScripts`:

```js
import { Collapse, Dropdown } from 'bootstrap-italia';
```

## Accessibility

All components comply with **WCAG 2.1 AA** and the AgID guidelines:

- Automatic skip-to-content link in the layout
- `aria-invalid`, `aria-describedby`, `aria-live="polite"` on forms with errors
- `data-element="privacy-policy-link"` and `data-element="accessibility-link"` in the footer
- Visible focus on all interactive elements
- Compliant color contrast

For Italian PA websites, WCAG 2.1 AA compliance is a legal requirement
(Legge Stanca 4/2004).

## Tests

```bash
composer install
vendor/bin/phpunit
```

The package includes 275 tests with 561 assertions, covering:

- Rendering of all components
- Laravel validation error binding
- ARIA attributes and WCAG compliance
- Unique ID generation

## Contributing

Before opening a Pull Request:

1. Run `vendor/bin/pint` for automatic code formatting
2. Run `vendor/bin/phpunit` — all tests must pass
3. Write tests for new features
4. Use commit messages in the `[Module] Imperative verb` format

Follow the existing code style. For questions, open an Issue.

## License

Released under the **BSD-3-Clause** license. See [LICENSE](LICENSE).

## References

- [Developers Italia](https://developers.italia.it)
- [Bootstrap Italia](https://italia.github.io/bootstrap-italia)
- [Designers Italia](https://designers.italia.it)
- [Design guidelines](https://docs.italia.it/italia/designers-italia/design-linee-guida-docs/)
- [Software catalog](https://developers.italia.it/it/software)

## Roadmap

- [x] Layout, Header, Footer
- [x] Icon, Button
- [x] Input, Select, Textarea
- [ ] Checkbox, Radio
- [ ] Alert, Card, Badge, Spinner
- [ ] Modal
- [ ] SPID / CIE buttons
- [ ] Publication in the Developers Italia catalog

---

Developed by [Igor Smoleac](https://github.com/igorsmoleac).
