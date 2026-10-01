# Changelog

All notable changes to `design-laravel-kit` will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added

### Changed
- Input, Radio, Checkbox, Select: il parametro `value` / `selected` ora accetta `string|int|float|\BackedEnum|null` invece del solo `string`.
- Card: aggiunto il parametro `headingLevel` (default `3`, range `2-6`) per controllare il livello del titolo; il sottotitolo ora è un `<p class="card-subtitle">` invece di `<h6>`, per evitare salti nella gerarchia dei titoli (WCAG 1.3.1).
- Modal: l'attributo `aria-describedby` è ora opzionale e viene emesso solo quando il parametro `description` è specificato, per evitare la lettura completa del `modal-body` da parte degli screen reader. Aggiunto il parametro `description` e documentato l'uso obbligatorio di `id` con un esempio in README.

### Fixed
- Input e Textarea: il label floating viene ora attivato anche quando è presente un `placeholder`, evitando la sovrapposizione con il testo di esempio.
- Footer: le legal link accettano ora `dataElement`, `data-element` e `data_element` per l'attributo HTML `data-element` (prima era supportato solo camelCase).

## [0.3.0] - 2026-10-01

### Changed (breaking)
- `RadioGroup` e `CheckboxGroup`: il parametro `errorBag` è stato rinominato in `bag`, per uniformità con gli altri componenti form. Chi utilizza `:error-bag="..."` deve passare a `:bag="..."`.

### Changed
- Refactoring architetturale interno: `BaseComponent` ora gestisce solo la generazione degli ID HTML; il nuovo `BaseFormComponent` fornisce l'infrastruttura per la validazione dei form. Nessun impatto sull'API pubblica.
- Rimossi metodi morti (`ariaAttributes()`, `isRequired()`) non utilizzati nei template.

## [0.2.0] - 2026-09-30

### Changed
- La versione negli URL degli asset (`?v=`) ora è risolta automaticamente da `Composer\InstalledVersions` invece che da un valore hardcoded nel file di configurazione.
- Tutte le stringhe UI ora passano attraverso `__()` con namespace `design-laravel-kit::`; aggiunti file `lang/it.json` e `lang/en.json` traducibili via `vendor:publish --tag=design-laravel-kit-lang`.
- Chiarita la formulazione dello scope in `SECURITY.md`: il pacchetto non elabora input lato server, ma **renderizza** dati forniti dall'utente (vecchio input, errori di validazione, URL).

### Fixed
- Select: opzione con chiave vuota non viene più marcata come `selected` in assenza di selezione; supporto per `Collection` e `Arrayable` in `:options`.
- Radio e checkbox in gruppo: l'ID dell'errore del gruppo ora è incluso nell'`aria-describedby` degli input figli, per garantire l'annuncio dello stato di errore da parte degli screen reader.
- Header center: gli attributi `class`, `id` e `data-*` passati al componente ora vengono applicati all'elemento radice.

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

