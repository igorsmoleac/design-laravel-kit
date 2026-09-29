# Changelog

All notable changes to `design-laravel-kit` will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Fixed
- Select: opzione con chiave vuota non viene più marcata come `selected` in assenza di selezione; supporto per `Collection` e `Arrayable` in `:options`.
- Radio e checkbox in gruppo: l'ID dell'errore del gruppo ora è incluso nell'`aria-describedby` degli input figli, per garantire l'annuncio dello stato di errore da parte degli screen reader.

## [0.1.0] - 2026-09-29

### Added
- Skeleton iniziale del pacchetto
- `DesignLaravelKitServiceProvider` con registrazione di config, views, component namespace e pubblicazione di config/views/assets
- `BaseComponent` astratto con generazione di ID univoci, attributi ARIA e binding errori di validazione
- `config/design-laravel-kit.php` con `id_prefix`, `assets_path` e `version`
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
- Componente `<x-italia::header>` che combina header-slim, header-center e header-navbar in un unico wrapper
- Componente `<x-italia::layout>` con header, main, footer e skip-link accessibile
- Documentazione bilingue: `README.md` (italiano) e `README.en.md` (inglese)
- Componenti `<x-italia::checkbox>` e `<x-italia::radio>` con binding degli errori di validazione
- Componenti `<x-italia::alert>`, `<x-italia::badge>` e `<x-italia::spinner>` con enum `AlertVariant`
- Componente `<x-italia::card>` con titolo, immagine, azioni e link
- Componente `<x-italia::modal>` con ARIA completo, focus trap (BI JS) e slot footer
- Componenti `<x-italia::spid-button>` e `<x-italia::cie-button>` con loghi ufficiali, dimensioni e stile dedicato `dlk-`
- Componenti `<x-italia::radio-group>` e `<x-italia::checkbox-group>` con fieldset, legend e singolo messaggio di errore

### Changed
- Logica comune dei campi form (errori, old input, label, ARIA) estratta nel trait `HandlesFormField`

### Fixed
- Burger del navbar invisibile su mobile in uso standalone: `.it-header-navbar-wrapper` ha sfondo blu solo ≥992px, quindi l'icona bianca risultava su fondo bianco — su mobile l'icona del toggler ora è `#06c` (fuori da `it-header-wrapper`)
- Floating label sovrappone il contenuto soprastante (es. titoli): `.dlk-floating` riserva `2.5rem` sopra il campo; tra campi consecutivi il margine collassa e resta `3rem`
- Colore del testo in it-footer-small-prints (copyright visibile)

### Dependencies
- Bootstrap Italia 2.18.3 (pinned, no `^`)
- Laravel 12/13
- Orchestra Testbench 11.3.0
- PHPUnit 12.5.36
- Laravel Pint 1.32.1
- PHP 8.3+
- Node.js 22 (build only)
- Vite 8.3.1 (build only)

