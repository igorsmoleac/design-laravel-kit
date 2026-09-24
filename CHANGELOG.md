# Changelog

All notable changes to `design-laravel-kit` will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added
- Initial package skeleton
- Abstract `BaseComponent` — unique ID generation, ARIA attributes, `$errors` binding, configurable component prefix
- `config/design-laravel-kit.php` — `views_namespace`, `component_prefix`, `id_prefix`, `assets_path`
- Service provider: `mergeConfigFrom`, view namespace registration, `Blade::componentNamespace` (`<x-italia::*>`), publishable config and views
- `design-laravel-kit:publish-assets` and `design-laravel-kit:install` commands with `--force` support
- `@designLaravelKitStyles` / `@designLaravelKitScripts` Blade directives
- `design-laravel-kit-assets` publish tag; built assets committed to the repository

### Changed
- Generated ids are unique per component instance (`dlk-email-12345` via `spl_object_id()`) — required by WCAG 2.1 AA / Legge Stanca
- `BaseComponent::prefix()` renamed to `idPrefix()`; error lookup decoupled from `idSeed()` into `errorField()`

