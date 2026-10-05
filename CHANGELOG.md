# Changelog

All notable changes to `design-laravel-kit` will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added
- Direttiva @designLaravelKitScripts: supporto nonce per Content Security Policy restrittive; il nonce può essere passato come argomento o configurato in config/design-laravel-kit.php (csp_nonce).
- Layout: parametro `showFooter` (default true) per disattivare il rendering del footer in pagine come login o layout minimali.

### Changed
- HeaderNavbar: il rilevamento del megamenu non usa più str_contains sull'HTML; un flag esplicito evita falsi positivi quando una normale voce di menu contiene il testo 'dropdown megamenu'.

### Fixed
- SpidButton: la lista di provider SPID ora viene validata; un provider senza 'name' o 'url' genera un'eccezione esplicita invece di un errore PHP poco chiaro.

## [0.5.1] - 2026-10-05

### Added
- Layout: parametro `csrf` (default `true`) per disattivare il meta tag csrf-token nelle pagine pubbliche cacheabili su CDN.

### Changed
- Textarea: il parametro `value` ora accetta `string|int|float|\BackedEnum|null`, coerente con Input, Radio, Checkbox e Select.

### Fixed
- ConfigValidator: la validazione URL ora accetta `#fragment`, `mailto:`, `tel:`, percorsi relativi e URL con caratteri non-ASCII; le schemi pericolose (`javascript:`, `data:`, `vbscript:`, `file:`) restano rifiutate. Ripristina la compatibilità con 0.4.x per i link di navigazione e footer.
- PublishAssetsCommand: la validazione di `assets_path` ora richiede una sottocartella dopo il prefisso (`vendor/`, `assets/`, `build/`). Percorsi come `vendor/` o `build/` vengono rifiutati per evitare la cancellazione di asset di altri pacchetti durante `--force`.
- Componenti helper (`header-nav-item`, `header-social-link`, `footer-legal-link`, `footer-social-link`, `footer-section`): ripristinata la propagazione degli attributi HTML (`class`, `id`, `target`, `rel`, `data-*`) all'elemento radice.
- Componenti helper: rimossi i default `= ''` dai parametri obbligatori, che mascheravano l'obbligatorietà al IDE e facevano fallire il rendering invece della costruzione.
- Footer e HeaderCenter: il titolo è ora opzionale; quando manca, il blocco brand-text non viene renderizzato e il componente non fallisce. `<x-italia::layout />` funziona anche senza `config('app.name')`.
- Button, Card, Badge, SpidButton, CieButton: aggiunta la validazione delle schemi URL, coerente con i componenti DTO. `javascript:`, `data:` (tranne `data:image/*` per `Card::$image`) e altre schemi pericolose vengono rifiutate.
- Componenti SPID/CIE/Header: le stringhe UI (etichette, aria-label) ora passano attraverso `__()` con namespace `design-laravel-kit::`; aggiunte le traduzioni in `en.json`.
- `publiccode.yml`: aggiornata `softwareVersion` a 0.5.0 e `releaseDate`; `SECURITY.md`: aggiornata la tabella delle versioni supportate; README: rimossa la descrizione obsoleta del parametro `version`, aggiornato il peso del bundle e la tabella dei componenti.

## [0.5.0] - 2026-10-02

### Changed (breaking)
- Layout, Header, HeaderSlim, HeaderCenter, HeaderNavbar, Footer: rimossi i parametri array (`:slim="[…]"`, `:center="[…]"`, `:navbar="[…]"`, `:footer="[…]"`); le configurazioni ora si passano tramite slot Blade con attributi denominati. Le classi DTO readonly interne (`SlimConfig`, `CenterConfig`, `NavbarConfig`, `FooterConfig`, `LegalLinkConfig`, `SocialLinkConfig`, `NavItemConfig`) sostituiscono gli array nella costruzione delle strutture. La megamenu dell'HeaderNavbar è ora configurata tramite `x-italia::header-megamenu` e `x-italia::header-megamenu-section`. Vedi "Migrazione da 0.4.x" nel README.

### Added
- Componenti `x-italia::header-nav-item`, `x-italia::header-megamenu`, `x-italia::header-megamenu-section`, `x-italia::header-social-link` per l'API a slot dell'header.
- Componenti `x-italia::footer-legal-link`, `x-italia::footer-section`, `x-italia::footer-social-link` per l'API a slot del footer.
- Form: nuovo parametro `wrapperClass` per applicare classi all'elemento di wrapping (grid, utility Bootstrap).
- SpidButton: se `dropdown` è attivo e `providers` è vuoto, viene usato l'elenco predefinito dei provider AgID (configurabile via `config/design-laravel-kit.php`).
- Layout: la configurazione del footer è ora accessibile tramite attributi dello slot `footer` (`title`, `subtitle`, `logo`, `logo-alt`, `url`, `copyright`) e quattro slot aggiuntivi (`footer-sections`, `footer-contacts`, `footer-social`, `footer-legal-links`).

### Changed
- Accessibilità: uniformato l'uso di `role="alert"` nei blocchi di errore; rimossa la combinazione contraddittoria con `aria-live="polite"`.

### Fixed
- ConfigValidator: accetta `data:image/*` URI per il campo `logo` (ripristina la compatibilità con 0.4.x); gli altri campi URL restano limitati a http(s) e percorsi root-relative.
- HeaderCenter, Footer: `hasLogo()` restituisce `false` anche per stringa vuota, evitando `<img src="">`.
- Layout: ripristinato il passaggio completo dei parametri al footer (subtitle, logo, url, sections, contacts, social, legal-links, copyright) tramite slot e attributi nominati.

## [0.4.3] - 2026-10-02

### Added
- IconSize: aggiunto il valore `xs` (16px), allineato a Bootstrap Italia 2.18.3.

### Changed
- PublishAssetsCommand: ampliati i prefissi di pubblicazione consentiti (`vendor/`, `assets/`, `build/`); la protezione contro path traversal resta attiva.

## [0.4.2] - 2026-10-02

### Added
- Analisi statica con Larastan a livello 6 su `src/` e `config/`; comando `composer analyse` e step dedicato nella matrice CI PHP/Laravel.

### Changed
- `HandlesFormField` suddiviso in tre trait: `HandlesFormField` (label, ID, ARIA), `HandlesCheckableField` (checkbox/radio), `HandlesFieldValue` (valori e old input).
- Risoluzione delle view dei componenti centralizzata in `BaseComponent::resolveView()`; i componenti non duplicano più il percorso.
- Tipi PHP esplicitati in docblock e firme per soddisfare Larastan livello 6 (nessun cambio di API pubblica).

### Fixed
- Componente Alert: corretto il livello del titolo da `<h4>` a `<h3>` per rispettare la gerarchia dei titoli.

## [0.4.1] - 2026-10-01

### Fixed
- Ricostruita la demo statica per GitHub Pages con il catalogo aggiornato (stile `.catalog-example`, layout Card/Select).

## [0.4.0] - 2026-10-01

### Added
- Documentazione sulle limitazioni degli ID generati (`spl_object_id`): stabili solo all'interno dello stesso render.
- CI: verifica che `resources/dist/` sia sincronizzato con le sorgenti (fallisce se manca `npm run build` dopo modifiche a CSS/JS).
- Documentazione sull'implementazione dei pulsanti SPID/CIE: non sono 1:1 con il kit ufficiale `italia/spid-sp-access-button`.

### Changed
- Aggiunta la dipendenza esplicita `illuminate/console` in `composer.json` (era usata transitivamente).
- Input, Radio, Checkbox, Select: il parametro `value` / `selected` ora accetta `string|int|float|\BackedEnum|null` invece del solo `string`.
- Card: specificare `href` senza `title` ora genera `InvalidArgumentException`. Prima `href` veniva silenziosamente ignorato. Passare sempre `title` insieme a `href`.
- Card: aggiunto il parametro `headingLevel` (default `3`, range `2-6`) per controllare il livello del titolo; il sottotitolo ora è un `<p class="card-subtitle">` invece di `<h6>`, per evitare salti nella gerarchia dei titoli (WCAG 1.3.1).
- Modal: l'attributo `aria-describedby` è ora opzionale e viene emesso solo quando il parametro `description` è specificato, per evitare la lettura completa del `modal-body` da parte degli screen reader. Aggiunto il parametro `description` e documentato l'uso obbligatorio di `id` con un esempio in README.

### Fixed
- Card: l'attributo `alt` dell'immagine è ora vuoto per default (`alt=""`) quando `imageAlt` non è specificato, evitando la duplicazione del titolo nell'annuncio degli screen reader.
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

