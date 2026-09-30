# Design Laravel Kit

[![License: BSD-3-Clause](https://img.shields.io/badge/License-BSD%203--Clause-blue.svg)](https://opensource.org/licenses/BSD-3-Clause)
[![Laravel 12|13](https://img.shields.io/badge/Laravel-12%20%7C%2013-red.svg)](https://laravel.com)
[![PHP 8.3+](https://img.shields.io/badge/PHP-8.3%2B-blue.svg)](https://php.net)
[![Tests](https://github.com/igorsmoleac/design-laravel-kit/actions/workflows/tests.yml/badge.svg)](https://github.com/igorsmoleac/design-laravel-kit/actions)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/igorsmoleac/design-laravel-kit.svg?style=flat-square)](https://packagist.org/packages/igorsmoleac/design-laravel-kit)

🇮🇹 **Italiano** | [🇬🇧 English](README.en.md)

Design Laravel Kit è un pacchetto Composer per applicazioni Laravel della Pubblica Amministrazione italiana. Espone componenti Blade basati su Bootstrap Italia 2.18.3 per layout istituzionali, navigazione, moduli, messaggi e accesso tramite SPID e CIE. Il bundle distribuito è circa 250 KB gzip; Node.js serve solo per compilare gli asset del pacchetto.

È destinato ad agenzie digitali, system integrator e sviluppatori che realizzano siti e servizi digitali per enti pubblici. Il pacchetto fornisce componenti e asset; la verifica di conformità del servizio resta a carico del progetto che lo integra.

## Caratteristiche

- Componenti Blade basati su Bootstrap Italia `2.18.3`
- Header istituzionale a tre livelli: slim, center e navbar
- Footer con sezioni, contatti e link legali con attributi `data-element`
- Campi form con binding degli errori di validazione e del vecchio input Laravel
- Attributi ARIA, identificativi generati e link salta-contenuto nel layout
- Pulsanti di accesso SPID e CIE con i rispettivi loghi
- Pubblicazione di CSS, JavaScript, font e sprite SVG tramite comando Artisan
- Compatibilità dichiarata con Laravel 12 e 13 e PHP 8.3

## Requisiti

| Componente | Versione |
|------------|----------|
| PHP | `^8.3` |
| Laravel | `^12.0 \| ^13.0` (`illuminate/support`, `illuminate/view`) |
| Node.js (solo build) | `^22.0` |
| Bootstrap Italia | `2.18.3` (versione fissata) |

Node.js non è richiesto nell'applicazione Laravel in esecuzione. Serve soltanto per compilare gli asset durante lo sviluppo del pacchetto.

## Installazione

```bash
composer require igorsmoleac/design-laravel-kit
php artisan design-laravel-kit:install
```

Il comando `install` pubblica la configurazione e gli asset CSS, JavaScript, font e sprite SVG in `public/vendor/design-laravel-kit/`.

Aggiungere le direttive al layout Blade:

```blade
<head>
    @designLaravelKitStyles
</head>
<body>
    @designLaravelKitScripts
</body>
```

Per pubblicare separatamente la configurazione:

```bash
php artisan vendor:publish --tag=design-laravel-kit-config
```

Per aggiornare il pacchetto, eseguire `composer update igorsmoleac/design-laravel-kit` e ripubblicare gli asset:

```bash
php artisan design-laravel-kit:publish-assets --force
```

L'opzione `--force` sovrascrive gli asset già pubblicati.

## Configurazione

Il comando `install` pubblica `config/design-laravel-kit.php`. Se il file di configurazione esiste già, Laravel non lo sovrascrive. Per forzarne la pubblicazione:

```bash
php artisan vendor:publish --tag=design-laravel-kit-config --force
```

| Chiave | Tipo | Default | Descrizione |
|--------|------|---------|-------------|
| `id_prefix` | `string` | `'dlk'` | Prefisso degli ID HTML generati dai componenti |
| `assets_path` | `string` | `'vendor/design-laravel-kit'` | Percorso pubblico degli asset |
| `version` | `string` | `'0.1.0'` | Versione aggiunta agli URL degli asset per il cache-busting |

### Traduzioni

Il pacchetto include file di traduzione in italiano e inglese. Per personalizzarli o aggiungere altre lingue:

```bash
php artisan vendor:publish --tag=design-laravel-kit-lang
```

I file saranno copiati in `lang/vendor/design-laravel-kit/`.

## Utilizzo

### Layout istituzionale

Configurare l'ente, la navigazione, i link legali e il contenuto della pagina:

```blade
<x-italia::layout
    title="Comune di Roma — Portale istituzionale"
    :slim="[
        'ente' => 'Comune di Roma',
        'enteUrl' => 'https://www.comune.roma.it',
        'loginUrl' => '/login',
    ]"
    :center="[
        'title' => 'Comune di Roma',
        'tagline' => 'Portale istituzionale',
        'url' => '/',
        'searchUrl' => '/cerca',
    ]"
    :navbar="[
        'items' => [
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
    <h1>Servizi comunali</h1>
    <p>Informazioni e servizi per i cittadini.</p>
</x-italia::layout>
```

### Form di segnalazione

I componenti form collegano gli errori della sessione Laravel e ripristinano i valori inviati in precedenza:

```blade
<form method="POST" action="{{ route('segnalazioni.store') }}">
    @csrf
    <x-italia::input name="email" type="email" label="Indirizzo email" required />
    <x-italia::textarea name="messaggio" label="Descrizione della segnalazione" :rows="5" required />
    <x-italia::button type="submit">Invia segnalazione</x-italia::button>
</form>
```

### Header in una vista esistente

Usare il componente header senza adottare il componente layout:

```blade
<x-italia::header
    :slim="['ente' => 'Azienda sanitaria locale']"
    :center="['title' => 'Servizi sanitari', 'searchUrl' => '/cerca']"
    :navbar="['items' => [['text' => 'Prenotazioni', 'url' => '/prenotazioni']]]"
/>
```

### Pulsante in caricamento

Impostare `loading` durante l'invio di una richiesta:

```blade
<x-italia::button type="submit" loading>
    Invio della richiesta in corso
</x-italia::button>
```

## Componenti

I componenti Blade usano il prefisso `<x-italia::`.

| Componente | Descrizione |
|------------|-------------|
| `<x-italia::layout>` | Layout di pagina con header, contenuto, footer e link salta-contenuto |
| `<x-italia::header>` | Header composto dai livelli slim, center e navbar |
| `<x-italia::footer>` | Footer con sezioni, contatti, social link e link legali |
| `<x-italia::icon>` | Icona dallo sprite SVG di Bootstrap Italia |
| `<x-italia::button>` | Pulsante o link con varianti, dimensioni e stato di caricamento |
| `<x-italia::input>` | Campo di input con label, hint e binding degli errori |
| `<x-italia::select>` | Elenco a discesa con opzioni, optgroup e selezione multipla |
| `<x-italia::textarea>` | Campo multiriga con binding degli errori |
| `<x-italia::checkbox>` | Casella di controllo singola |
| `<x-italia::radio>` | Pulsante radio singolo |
| `<x-italia::checkbox-group>` | Gruppo di checkbox con fieldset e legend |
| `<x-italia::radio-group>` | Gruppo di radio con fieldset e legend |
| `<x-italia::alert>` | Messaggio di stato: info, success, warning o danger |
| `<x-italia::badge>` | Etichetta di stato con variante configurabile |
| `<x-italia::card>` | Card con contenuto, link e varianti |
| `<x-italia::spinner>` | Indicatore di caricamento con etichetta accessibile configurabile |
| `<x-italia::modal>` | Finestra di dialogo con attributi ARIA e supporto JavaScript Bootstrap Italia |
| `<x-italia::spid-button>` | Pulsante di accesso SPID |
| `<x-italia::cie-button>` | Pulsante di accesso CIE |

Gli esempi d'uso dei componenti sono nel [catalogo del playground](https://github.com/igorsmoleac/design-laravel-kit/blob/main/playground/resources/views/catalog.blade.php), usato per lo sviluppo.

## Accessibilità

I componenti includono link salta-contenuto, label associate ai campi, attributi ARIA per gli errori dei form e attributi `data-element` per i link legali del footer. Il riferimento normativo per l'accessibilità dei servizi digitali della PA è la [Legge 9 gennaio 2004, n. 4 (Legge Stanca)](https://www.normattiva.it/eli/id/2004/01/17/004G0015/CONSOLIDATED/20231118). WCAG 2.1 livello AA è il criterio di riferimento dichiarato; il pacchetto non certifica la conformità del sito o del servizio che lo integra.

<a id="limitazioni-note"></a>Limitazioni note: il focus trap del modal è gestito dal JavaScript di Bootstrap Italia e non è testato dal pacchetto; l'annuncio degli errori associati ai fieldset può variare con screen reader meno recenti; il contrasto ereditato da Bootstrap Italia 2.18.3 non è verificato automaticamente nel CI con axe-core o pa11y.

## Test

Eseguire dalla directory del pacchetto:

```bash
vendor/bin/phpunit
vendor/bin/pint --test
```

La suite verifica il rendering dei componenti, il binding di errori e vecchi valori Laravel, gli attributi ARIA, la generazione degli ID, i comandi di pubblicazione e le direttive Blade per gli asset.

## Contribuire

Consultare le [linee guida per contribuire](CONTRIBUTING.md) prima di inviare modifiche.

## Sicurezza

Per segnalare una vulnerabilità, seguire le istruzioni in [SECURITY.md](SECURITY.md).

## Changelog

La cronologia delle modifiche è disponibile in [CHANGELOG.md](CHANGELOG.md).

## Licenza

Il codice del pacchetto è rilasciato con licenza [BSD-3-Clause](LICENSE), che consente l'uso, la modifica e la ridistribuzione nel rispetto delle condizioni della licenza.

I loghi SPID e CIE inclusi nei rispettivi pulsanti sono marchi dei titolari, non coperti dalla BSD-3-Clause: il logo SPID è fornito da [AgID](https://github.com/italia/spid-sp-access-button) e il logo CIE dal [Ministero dell'Interno](https://idserver.servizicie.interno.gov.it/idp/images/cielogo.png). Il loro uso è limitato al contesto previsto dalle indicazioni dei titolari.

## Riferimenti

- Il design system incluso è [Bootstrap Italia 2.18.3](https://italia.github.io/bootstrap-italia).
- [Developers Italia](https://developers.italia.it) e [Catalogo del software](https://developers.italia.it/it/software)
- [Designers Italia](https://designers.italia.it) e [Linee guida di design](https://docs.italia.it/italia/designers-italia/design-linee-guida-docs/)

---

Sviluppato da [Igor Smoleac](https://github.com/igorsmoleac) — [rekeenstudio@gmail.com](mailto:rekeenstudio@gmail.com)
