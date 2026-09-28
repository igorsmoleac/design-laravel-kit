# Contributing

Thanks for considering a contribution to Design Laravel Kit.

## Requirements

- PHP 8.3+
- Laravel 12 or 13
- Node.js 20+ (only for rebuilding assets)
- Composer
- Git

## Setup

```bash
git clone https://github.com/igorsmoleac/design-laravel-kit
cd design-laravel-kit
composer install
npm install
npm run build
```

## Before opening a Pull Request

1. Run `vendor/bin/pint` to format code.
2. Run `vendor/bin/phpunit` — all tests must pass.
3. Add tests for new features — the project targets WCAG 2.1 AA.
4. Follow the commit format: `[Module] Imperative verb`, e.g. `[Button] Add loading state`.

## Code style

The project follows the maintainer's coding conventions. Key points:

- Method order: `__construct` → `render()` → public → protected → private
- Early return over nested conditionals
- No comments on obvious code
- Blade: avoid `@php`/`@endphp` when possible — use class methods

## Reporting bugs

Open an Issue with:

- What you expected
- What happened
- Minimal reproduction (Blade snippet + error)
- Laravel / PHP versions

## Suggesting features

Open an Issue describing the use case before opening a PR.
Not every feature fits the scope — the package targets Italian PA websites.

## License

By contributing, you agree that your contribution is released under
the BSD-3-Clause license, the same as the package.
