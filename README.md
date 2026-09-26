# Design Laravel Kit

[![License: BSD-3-Clause](https://img.shields.io/badge/License-BSD%203--Clause-blue.svg)](https://opensource.org/licenses/BSD-3-Clause)
[![Laravel 12|13](https://img.shields.io/badge/Laravel-12%20%7C%2013-red.svg)](https://laravel.com)
[![PHP 8.3+](https://img.shields.io/badge/PHP-8.3%2B-blue.svg)](https://php.net)
[![Tests](https://github.com/igorsmoleac/design-laravel-kit/actions/workflows/tests.yml/badge.svg)](https://github.com/igorsmoleac/design-laravel-kit/actions)

🇮🇹 **Italiano** | [🇬🇧 English](README.en.md)

Integrazione moderna di Bootstrap Italia per Laravel. Componenti Blade, Vite, accessibilità.

> Pacchetto in fase di sviluppo.

<!-- TODO: screenshots -->

## A cosa serve

Un pacchetto Composer per costruire siti e servizi digitali della Pubblica
Amministrazione italiana con Laravel. Fornisce componenti Blade pronti
all'uso, conformi alle Linee guida di design per i siti internet e i servizi
digitali della PA.

**Per chi è pensato:** agenzie digitali, system integrator e sviluppatori
che lavorano su progetti per enti pubblici — comuni, scuole, aziende
sanitarie, ministeri. L'obiettivo è ridurre il tempo di sviluppo dei
template istituzionali da giorni a poche ore.

**Cosa include:** header a tre livelli (slim, center, navbar), footer
conforme AgID, form con binding automatico degli errori di validazione
e attributi ARIA, layout completo per una pagina istituzionale.

## Requisiti

- PHP **8.3** o superiore
- Laravel **12** o **13**
- Node.js 20+ (solo per compilare gli asset del pacchetto — non serve per l'uso)

## Installazione

```bash
composer require igorsmoleac/design-laravel-kit
php artisan design-laravel-kit:install
```

Il comando `install` pubblica la configurazione e gli asset (CSS, JS,
font, sprite SVG) nella cartella `public/vendor/design-laravel-kit/`.

Aggiungere le direttive al proprio layout Blade:

```blade
<head>
    @designLaravelKitStyles
</head>
<body>
    ...
    @designLaravelKitScripts
</body>
```

## Avvio rapido

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

Questo produce una pagina completa conforme alle linee guida PA:
header a tre livelli, footer con link legali obbligatori,
skip-to-content link automatico, attributi ARIA.

## Componenti

Tutti i componenti usano il prefisso `<x-italia::`, configurabile con
l'opzione `component_prefix` in `config/design-laravel-kit.php`.

### Layout

Template completo per una pagina istituzionale: header, main, footer
e skip-to-content link.

```blade
<x-italia::layout title="..." :slim="[...]" :center="[...]" :navbar="[...]" :footer="[...]">
    <h1>Contenuto</h1>
</x-italia::layout>
```

### Header

Header a tre livelli: slim (ente e lingue), center (brand e ricerca),
navbar (menu di navigazione).

```blade
<x-italia::header
    :slim="['ente' => 'Nome della PA']"
    :center="['title' => 'Nome del sito', 'searchUrl' => '/search']"
    :navbar="['items' => [['text' => 'Home', 'url' => '/']]]"
/>
```

### Footer

Footer conforme AgID con sezioni, contatti e link legali obbligatori.

```blade
<x-italia::footer
    title="Nome della PA"
    :sections="[...]"
    :contacts="[...]"
    :legal-links="[
        ['url' => '/privacy', 'text' => 'Privacy policy', 'dataElement' => 'privacy-policy-link'],
        ['url' => '/accessibilita', 'text' => 'Dichiarazione di accessibilità', 'dataElement' => 'accessibility-link'],
    ]"
/>
```

### Icon

Icona dallo sprite SVG di Bootstrap Italia.

```blade
<x-italia::icon name="it-search" />
<x-italia::icon name="it-close" size="lg" />
<x-italia::icon name="it-user" label="Profilo utente" />
```

### Button

Pulsante con varianti, dimensioni e stati (loading, disabled, link).

```blade
<x-italia::button>Primary</x-italia::button>
<x-italia::button variant="secondary" size="lg">Secondary</x-italia::button>
<x-italia::button href="/login" variant="primary">Accedi</x-italia::button>
<x-italia::button loading>Caricamento…</x-italia::button>
```

### Input

Campo di input con binding automatico degli errori di validazione.

```blade
<x-italia::input name="email" type="email" label="Email" required />
<x-italia::input name="nome" label="Nome" hint="Il tuo nome completo" />
```

### Select

Elenco a discesa con supporto per optgroup, `multiple` e placeholder.

```blade
<x-italia::select name="provincia" label="Provincia" :options="[
    'rm' => 'Roma',
    'mi' => 'Milano',
    'na' => 'Napoli',
]" placeholder="Seleziona…" />
```

### Textarea

Campo multiriga con binding automatico degli errori di validazione.

```blade
<x-italia::textarea name="messaggio" label="Messaggio" :rows="5" />
```

## Design system

Il pacchetto si basa su **Bootstrap Italia 2.18.3**, la versione stabile
ufficiale della libreria di design per la Pubblica Amministrazione italiana.

**Perché non la v3?** La versione 3.0 è attualmente in beta e introduce
cambiamenti sostanziali al markup e ai token CSS. La v2.18.3 è stabile,
documentata e utilizzata in produzione dai siti della PA.

**Perché non design-tokens-italia separatamente?** Bootstrap Italia include
già i design token ufficiali nel proprio CSS. Aggiungerli separatamente
creerebbe duplicazione e conflitti.

## Prestazioni

Il pacchetto pubblica il bundle completo di Bootstrap Italia (~1 MB,
~250-300 KB gzip). Questo è il prezzo del zero-config: nessuna
configurazione Vite richiesta.

Se serve un bundle più piccolo, è possibile importare solo i componenti
necessari nel proprio progetto Vite e non usare `@designLaravelKitScripts`:

```js
import { Collapse, Dropdown } from 'bootstrap-italia';
```

## Accessibilità

Tutti i componenti sono conformi a **WCAG 2.1 AA** e alle linee guida AgID:

- Skip-to-content link automatico nel layout
- `aria-invalid`, `aria-describedby`, `aria-live="polite"` sui form con errori
- `data-element="privacy-policy-link"` e `data-element="accessibility-link"` nel footer
- Focus visibile su tutti gli elementi interattivi
- Contrasto colori conforme

Per i siti della PA italiana, la conformità WCAG 2.1 AA è un obbligo
di legge (Legge Stanca 4/2004).

## Test

```bash
composer install
vendor/bin/phpunit
```

Il pacchetto include 275 test con 561 asserzioni, che coprono:

- Rendering di tutti i componenti
- Binding degli errori di validazione Laravel
- Attributi ARIA e conformità WCAG
- Generazione di ID univoci

## Contribuire

Prima di aprire una Pull Request:

1. Eseguire `vendor/bin/pint` per la formattazione automatica
2. Eseguire `vendor/bin/phpunit` — tutti i test devono passare
3. Scrivere test per le nuove funzionalità
4. Usare messaggi di commit nel formato `[Modulo] Verbo all'imperativo`

Seguire lo stile del codice esistente. Per domande, aprire una Issue.

## Licenza

Rilasciato sotto licenza **BSD-3-Clause**. Vedi [LICENSE](LICENSE).

## Attribuzione dei loghi

Il pacchetto include i loghi ufficiali **SPID** e **CIE** utilizzati nei
pulsanti di accesso. Questi loghi sono **marchi registrati** dei rispettivi
titolari e non sono coperti dalla licenza BSD-3-Clause del pacchetto:

- **Logo SPID** — AgID (Agenzia per l'Italia Digitale).
  Fonte: [italia/spid-sp-access-button](https://github.com/italia/spid-sp-access-button).
  Utilizzato per lo scopo previsto: pulsante di accesso SPID conforme
  alle linee guida AgID.

- **Logo CIE** — Ministero dell'Interno.
  Fonte: [idserver.servizicie.interno.gov.it](https://idserver.servizicie.interno.gov.it/idp/images/cielogo.png).
  Utilizzato per lo scopo previsto: pulsante di accesso CIE conforme
  alle linee guida AgID.

Per l'uso dei loghi al di fuori del contesto previsto (pulsanti di
accesso), fare riferimento alle linee guida ufficiali AgID.

## Riferimenti

- [Developers Italia](https://developers.italia.it)
- [Bootstrap Italia](https://italia.github.io/bootstrap-italia)
- [Designers Italia](https://designers.italia.it)
- [Linee guida di design](https://docs.italia.it/italia/designers-italia/design-linee-guida-docs/)
- [Catalogo del software](https://developers.italia.it/it/software)

## Roadmap

- [x] Layout, Header, Footer
- [x] Icon, Button
- [x] Input, Select, Textarea
- [ ] Checkbox, Radio
- [ ] Alert, Card, Badge, Spinner
- [ ] Modal
- [ ] Pulsanti SPID / CIE
- [ ] Pubblicazione nel catalogo Developers Italia

---

Sviluppato da [Igor Smoleac](https://github.com/igorsmoleac).
