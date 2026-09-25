# Changelog

All notable changes to `design-laravel-kit` will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added
- Skeleton iniziale del pacchetto
- `DesignLaravelKitServiceProvider` con registrazione di config, views, component namespace e pubblicazione di config/views/assets
- `BaseComponent` astratto con generazione di ID univoci, attributi ARIA e binding errori di validazione
- `config/design-laravel-kit.php` con `component_prefix`, `id_prefix`, `views_namespace`, `assets_path`
- Asset Pipeline: comandi `design-laravel-kit:install` e `design-laravel-kit:publish-assets` (con supporto `--force`)
- Direttive Blade `@designLaravelKitStyles` e `@designLaravelKitScripts`
- Bundle Bootstrap Italia precompilato (JS + SVG sprite + fonts) tramite `vite-plugin-static-copy`
- Tag di pubblicazione `design-laravel-kit-assets`; asset compilati committati nel repository
- Componente `<x-italia::icon>` con risoluzione sprite SVG e supporto ARIA
- Componente `<x-italia::button>` con varianti, dimensioni e stati (loading, disabled, link)
- Componente `<x-italia::input>` con binding `$errors`, ARIA, floating label e supporto error bag nominati
- Componente `<x-italia::select>` con binding `$errors`, optgroup, placeholder, `multiple` (auto `[]`) e ARIA
- Componente `<x-italia::textarea>` con binding `$errors`, ARIA, floating label e valore renderizzato tra i tag
- Componente `<x-italia::header-slim>` con link, selettore lingua e login
- Componente `<x-italia::header-center>` con brand, tagline, social links e ricerca

### Changed
- Logica comune dei campi form (errori, old input, label, ARIA) estratta nel trait `HandlesFormField`
- ID dei componenti ora usano `spl_object_id()` — nessun rischio di collisione (WCAG 2.1 AA / Legge Stanca)
- `prefix()` rinominato in `idPrefix()`
- `fieldName()` rinominato in `errorField()`, slegato da `idSeed()`

### Fixed
- Floating label sovrappone il contenuto soprastante (es. titoli): `.dlk-floating` riserva `2.5rem` sopra il campo; tra campi consecutivi il margine collassa e resta `3rem`

### Dependencies
- Bootstrap Italia 2.18.3 (pinned, no `^`)
- Laravel 12/13
- Orchestra Testbench 11.3.0
- PHPUnit 12.5.35
- Laravel Pint 1.32.1
- PHP 8.3+
- Node.js 20 (build only)

