# Design Laravel Kit

[![License: BSD-3-Clause](https://img.shields.io/badge/License-BSD%203--Clause-blue.svg)](https://opensource.org/licenses/BSD-3-Clause)
[![Laravel 12|13](https://img.shields.io/badge/Laravel-12%20%7C%2013-red.svg)](https://laravel.com)
[![PHP 8.3+](https://img.shields.io/badge/PHP-8.3%2B-blue.svg)](https://php.net)
[![Tests](https://github.com/igorsmoleac/design-laravel-kit/actions/workflows/tests.yml/badge.svg)](https://github.com/igorsmoleac/design-laravel-kit/actions)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/igorsmoleac/design-laravel-kit.svg?style=flat-square)](https://packagist.org/packages/igorsmoleac/design-laravel-kit)

🌐 **[Demo interattiva](https://igorsmoleac.github.io/design-laravel-kit/)**

🇮🇹 **Italiano** | [🇬🇧 English](README.en.md)

Design Laravel Kit è un pacchetto Composer per applicazioni Laravel della Pubblica Amministrazione italiana. Espone componenti Blade basati su Bootstrap Italia 2.18.3 per layout istituzionali, navigazione, moduli, messaggi e accesso tramite SPID e CIE. Il bundle distribuito è circa 375 KB gzip; Node.js serve solo per compilare gli asset del pacchetto.

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
| `csp_nonce` | `?string` | `null` | Nonce per i tag `<script>` quando l'app usa CSP restrittive |

La versione degli asset è risolta automaticamente da `Composer\InstalledVersions`.

`assets_path` deve includere una sottocartella del pacchetto sotto `vendor/`, `assets/` o `build/`, ad esempio `vendor/design-laravel-kit`; non impostarlo sul solo prefisso.
Questo evita che `--force` cancelli gli asset di altri pacchetti.

Se l'applicazione usa una Content Security Policy restrittiva, impostare `csp_nonce` o passare il nonce come argomento alla direttiva: `@designLaravelKitScripts($nonce)`. L'argomento ha precedenza sulla configurazione; un nonce con caratteri non validi (virgolette, spazi) genera un'eccezione.

### Traduzioni

Il pacchetto include file di traduzione in italiano e inglese. Per personalizzarli o aggiungere altre lingue:

```bash
php artisan vendor:publish --tag=design-laravel-kit-lang
```

I file saranno copiati in `lang/vendor/design-laravel-kit/`.

## Utilizzo

### Layout istituzionale

Configurare l'ente, la navigazione, i dati del footer, i suoi slot per sezioni, contatti, social e link legali, e il contenuto della pagina:

```blade
<x-italia::layout title="Comune di Roma — Portale istituzionale">
    <x-slot:slim
        ente="Comune di Roma"
        ente-url="https://www.comune.roma.it"
        login-url="/login"
    ></x-slot:slim>
    <x-slot:center
        title="Comune di Roma"
        tagline="Portale istituzionale"
        url="/"
        search-url="/cerca"
    ></x-slot:center>
    <x-slot:navbar>
        <x-italia::header-nav-item text="Amministrazione" url="/amministrazione" />
        <x-italia::header-nav-item text="Servizi" url="/servizi" />
    </x-slot:navbar>

    <x-slot:footer
        title="Comune di Roma"
        subtitle="Portale istituzionale"
        logo="/images/stemma.svg"
        logo-alt="Stemma comunale"
        url="/"
        copyright="© 2026 Comune di Roma"
    ></x-slot:footer>
    <x-slot:footer-sections>
        <x-italia::footer-section title="Amministrazione" url="/amministrazione">
            <li><a class="list-item" href="/amministrazione/giunta">Giunta comunale</a></li>
        </x-italia::footer-section>
    </x-slot:footer-sections>
    <x-slot:footer-contacts>
        <div class="col-lg-4 col-md-4 pb-2">
            <p>Via Roma 1, 00100 Roma</p>
            <a href="tel:+39060000000">+39 06 0000000</a>
            <a href="mailto:info@comune.it">info@comune.it</a>
        </div>
    </x-slot:footer-contacts>
    <x-slot:footer-social>
        <x-italia::footer-social-link url="https://facebook.com" icon="it-facebook" label="Facebook" />
    </x-slot:footer-social>
    <x-slot:footer-legal-links>
        <x-italia::footer-legal-link url="/privacy" text="Privacy policy" data-element="privacy-policy-link" />
        <x-italia::footer-legal-link url="/accessibilita" text="Dichiarazione di accessibilità" data-element="accessibility-link" />
    </x-slot:footer-legal-links>

    <h1>Servizi comunali</h1>
    <p>Informazioni e servizi per i cittadini.</p>
</x-italia::layout>
```

Il parametro `csrf` (default `true`) aggiunge il meta tag csrf-token. Impostare `:csrf="false"` per pagine pubbliche cacheabili su CDN.

Il parametro `showFooter` (default `true`) controlla il rendering del footer. Impostare `:show-footer="false"` per pagine senza footer (login, layout minimali).

### Form di segnalazione

I componenti form collegano gli errori della sessione Laravel e ripristinano i valori inviati in precedenza. `wrapper-class` applica classi al contenitore; `class` resta sul controllo:

```blade
<form method="POST" action="{{ route('segnalazioni.store') }}">
    @csrf
    <x-italia::input name="email" type="email" label="Indirizzo email" wrapper-class="col-md-6" required />
    <x-italia::textarea name="messaggio" label="Descrizione della segnalazione" :rows="5" required />
    <x-italia::button type="submit">Invia segnalazione</x-italia::button>
</form>
```

### Select con opzioni personalizzate

Le opzioni possono essere passate anche come slot Blade, che ha precedenza su `:options`:

```blade
<x-italia::select name="city" label="Città" placeholder="Tutte le città" :placeholder-disabled="false">
    <option value="mi">Milano</option>
    <option value="na">Napoli</option>
</x-italia::select>
```

Il parametro `placeholderDisabled` (default `true`) mantiene il placeholder non selezionabile come prima; con `:placeholder-disabled="false"` l'opzione vuota resta selezionabile, utile nei filtri (es. «Tutte le città»).

### Header in una vista esistente

Usare il componente header senza adottare il componente layout:

```blade
<x-italia::header>
    <x-slot:slim ente="Azienda sanitaria locale"></x-slot:slim>
    <x-slot:center title="Servizi sanitari" search-url="/cerca"></x-slot:center>
    <x-slot:navbar>
        <x-italia::header-nav-item text="Prenotazioni" url="/prenotazioni" />
    </x-slot:navbar>
</x-italia::header>
```

Per `logo` di HeaderCenter e Footer sono accettati URL HTTP(S), percorsi root-relative e URI `data:image/*`.

### Megamenu con link raggruppati

Usare `header-megamenu` dentro lo slot navbar e raggruppare i link in sezioni:

```blade
<x-italia::header-navbar>
    <x-italia::header-nav-item text="Home" url="/" active />
    <x-italia::header-megamenu text="Servizi" url="/servizi">
        <x-italia::header-megamenu-section heading="Anagrafe">
            <x-italia::header-nav-item text="Certificati" url="/certificati" />
            <x-italia::header-nav-item text="Residenza" url="/residenza" />
        </x-italia::header-megamenu-section>
        <x-italia::header-megamenu-section heading="Tributi">
            <x-italia::header-nav-item text="IMU" url="/imu" />
        </x-italia::header-megamenu-section>
    </x-italia::header-megamenu>
    <x-italia::header-nav-item text="Novità" url="/novita" />
</x-italia::header-navbar>
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
| `<x-italia::header-nav-item>` | Voce di navigazione per lo slot navbar |
| `<x-italia::header-megamenu>` | Megamenu per lo slot navbar, con sezioni di link raggruppate |
| `<x-italia::header-megamenu-section>` | Sezione di link del megamenu con intestazione |
| `<x-italia::header-social-link>` | Link social per HeaderCenter |
| `<x-italia::footer-legal-link>` | Link legale per il footer |
| `<x-italia::footer-section>` | Sezione del footer con contenuto nello slot |
| `<x-italia::footer-social-link>` | Link social per il footer |

I componenti helper dell'header e del footer accettano attributi HTML standard di Blade, come `class`, `id` e `data-*`.

### Riferimento parametri

Parametri dei componenti con API non banale. Gli attributi HTML comuni (`class`, `id`, `data-*`) vengono propagati all'elemento radice; i tipi sono quelli PHP.

| Parametro | Tipo | Descrizione |
|---|---|---|
| **`x-italia::layout`** | | |
| `title` | `string` | Titolo `<title>`; se vuoto, `config('app.name')` |
| `description` | `?string` | Meta description |
| `lang` | `?string` | Attributo `lang` di `<html>`; se null, locale dell'app |
| `light`, `sticky` | `bool` | Tema e comportamento sticky per l'header |
| `skipToContent`, `skipLabel` | `bool`, `?string` | Link «salta al contenuto» e sua etichetta |
| `bodyClass`, `mainClass` | `string`, `?string` | Classi di `<body>` e `<main>` |
| `footerTitle`, `footerSubtitle`, `footerLogo`, `footerLogoAlt`, `footerUrl`, `footerCopyright` | `?string` | Dati del brand per il footer; di norma via slot `footer` |
| `csrf` | `bool` | Meta tag csrf-token |
| `showFooter` | `bool` | Rendering del footer |
| Slot | | `slim`, `center`, `navbar`, `footer`, `footer-sections`, `footer-contacts`, `footer-social`, `footer-legal-links`, `breadcrumbs` |
| **`x-italia::header`** | | |
| `light`, `sticky`, `small` | `bool` | Tema, sticky e versione compatta |
| Slot | | `slim`, `center`, `navbar` |
| **`x-italia::header-center`** | | |
| `title`, `tagline` | `?string` | Nome dell'ente e tagline |
| `logo`, `logoAlt` | `?string` | Logo (HTTP(S), root-relative, `data:image/*`) e testo alt |
| `url` | `?string` | Link del brand |
| `searchUrl` | `?string` | La presenza abilita il form di ricerca |
| `small`, `light` | `bool` | Versione compatta e tema |
| Slot | | `social-links` |
| **`x-italia::card`** | | |
| `title`, `subtitle` | `?string` | Titolo e sottotitolo |
| `image`, `imageAlt` | `?string` | Immagine (anche `data:image/*`) e testo alt |
| `href` | `?string` | Link stretched-link; richiede `title` |
| `big`, `teaser` | `bool` | Varianti `card-big` e `card-teaser` |
| `headingLevel` | `int` | Livello del titolo, 2–6 (default 3) |
| Slot | | contenuto e `actions` |
| **`x-italia::alert`** | | |
| `variant` | `string\|AlertVariant` | `info` (default), `success`, `warning`, `danger`; valore sconosciuto → `info` |
| `title` | `?string` | Titolo |
| `dismissible` | `bool` | Pulsante di chiusura |
| `icon` | `bool` | Icona in base alla variante |
| **`x-italia::modal`** | | |
| `title` | `string` | Titolo, obbligatorio |
| `id` (attributo) | `string` | Necessario per collegare `data-bs-target` |
| `description` | `?string` | Testo collegato via `aria-describedby` |
| `size` | `?string` | `sm`, `lg`, `xl` |
| `centered`, `scrollable`, `static`, `dismissible` | `bool` | Comportamento della finestra |
| Slot | | contenuto e `footer` |
| **`x-italia::select`** | | |
| `name` | `string` | Nome del campo |
| `options` | `array\|Collection\|Arrayable` | `value => label`; array annidato → `optgroup` |
| `selected` | `string\|int\|float\|BackedEnum\|array\|null` | Valore o valori selezionati |
| `placeholder` | `?string` | Opzione vuota in testa |
| `placeholderDisabled` | `bool` | Default `true`; con `false` l'opzione vuota resta selezionabile (filtri) |
| `multiple` | `bool` | Selezione multipla, il nome riceve `[]` |
| `label`, `hint` | `?string` | Etichetta e suggerimento |
| `required`, `disabled`, `floating` | `bool` | Stato del campo |
| `bag` | `string` | Error bag |
| Slot | | `<option>` manuali; ha precedenza su `options` |
| **`x-italia::input`** | | |
| `name`, `type` | `string` | Nome e tipo del campo |
| `value` | `string\|int\|float\|BackedEnum\|null` | Valore del campo |
| `label`, `hint` | `?string` | Etichetta e suggerimento |
| `required`, `disabled`, `readonly`, `floating` | `bool` | Stato del campo |
| `bag` | `string` | Error bag |
| `wrapperClass` | `?string` | Classi del contenitore |

### Politica URL

I campi URL dei componenti accettano:

| Forma | Esempio |
|---|---|
| `http` / `https` | `https://www.comune.roma.it` |
| `mailto:` / `tel:` | `mailto:info@comune.it`, `tel:+39060000000` |
| Ancora | `#sezione` |
| Root-relative | `/servizi` |
| Relativo | `pagina.html`, `./pagine/` |
| Caratteri non ASCII | `/città/servizi` |

Sono rifiutati: `javascript:`, `vbscript:`, `file:`, `ftp:`, ogni altro schema non ammesso, `data:` (tranne `data:image/*` per `logo` e `Card::$image`) e la stringa vuota.

La validazione è applicata nei componenti helper (`header-nav-item`, `footer-legal-link`, `footer-social-link`, ecc.) e in `button`, `card`, `badge`, `spid-button`, `cie-button`. Un URL non valido genera `InvalidArgumentException` con l'elenco delle forme ammesse nel messaggio.

Gli slot `slim`, `center`, `navbar`, `footer`, `sections`, `social` e `legal-links` usano attributi Blade nominati, non array. Anche `HeaderSlim`, `HeaderCenter` e `HeaderNavbar` autonomi usano attributi scalari e slot, non array. I DTO readonly (`SlimConfig`, `CenterConfig`, ecc.) sono dettagli interni: non vanno passati dai template.

## Migrazione da 0.4.x

I componenti `Layout`, `Header` e `Footer` non accettano più array di configurazione. Sostituire gli attributi array con slot e componenti helper:

| Componente | Prima (0.4.x) | Ora |
|---|---|---|
| Layout | `<x-italia::layout :slim="$slim" :center="$center" :navbar="$navbar" :footer="$footer" />` | `<x-italia::layout><x-slot:slim ente="Comune di Roma"></x-slot:slim><x-slot:center title="Comune di Roma"></x-slot:center><x-slot:navbar><x-italia::header-nav-item text="Servizi" url="/servizi" /></x-slot:navbar><x-slot:footer title="Comune di Roma"><x-italia::footer-legal-link url="/privacy" text="Privacy" /></x-slot:footer></x-italia::layout>` |
| Header | `<x-italia::header :slim="$slim" :center="$center" :navbar="$navbar" />` | `<x-italia::header><x-slot:slim ente="Comune di Roma"></x-slot:slim><x-slot:center title="Comune di Roma"></x-slot:center><x-slot:navbar><x-italia::header-nav-item text="Servizi" url="/servizi" /></x-slot:navbar></x-italia::header>` |
| Footer | `<x-italia::footer title="Comune" :legal-links="$legal" />` | `<x-italia::footer title="Comune"><x-slot:legal-links><x-italia::footer-legal-link url="/privacy" text="Privacy" /></x-slot:legal-links></x-italia::footer>` |

Comportamenti cambiati rispetto a 0.4.x:

- **Validazione URL**: gli URL non validi generano ora `InvalidArgumentException` invece di essere renderizzati così come sono (vedi «Politica URL»).
- **Voce di navigazione senza `url`**: una voce normale senza `url` genera un'eccezione esplicita; le voci `dropdown` mantengono il fallback `href="#"`.
- **Slot self-closing**: `<x-slot:slim />` non è supportato in Laravel 13; usare la forma esplicita `<x-slot:slim></x-slot:slim>`.

### Nota sui pulsanti SPID e CIE

I pulsanti `<x-italia::spid-button>` e `<x-italia::cie-button>` forniscono un'implementazione autonoma con colori, dimensioni e spaziature ragionevoli, basata sui loghi ufficiali AgID. **Non sono 1:1 con il kit ufficiale [spid-sp-access-button](https://github.com/italia/spid-sp-access-button)**, che specifica dimensioni, colori per ogni stato (normal/hover/focus/pressed) e loghi per stato.

Per una conformità AgID rigorosa su siti PA in produzione:

- usare la libreria ufficiale `italia/spid-sp-access-button` insieme a questo pacchetto, oppure
- sovrascrivere le classi `.dlk-spid-button` e `.dlk-cie-button` con CSS personalizzato conforme alla specifica ufficiale.

#### Dropdown SPID

Con `dropdown` attivo e `providers` vuoto, viene usato `config('design-laravel-kit.spid.providers')`. È possibile sostituire l'elenco e adattare gli URL alle route SPID dell'applicazione:

```php
'spid' => [
    'providers' => [
        ['name' => 'Poste Italiane', 'url' => '/spid/login/poste'],
    ],
],
```

```blade
<x-italia::spid-button dropdown />
```

### Note sugli ID

I componenti generano ID HTML univoci tramite `spl_object_id()`. Gli ID sono **stabili all'interno di un singolo render** ma **non tra richieste diverse**. Se il rendering di un componente viene cachato separatamente (es. `Cache::remember`), possono verificarsi collisioni di ID. In questo caso passare un ID esplicito via attributo `id="..."`. Con il morphing DOM di Livewire, passare un ID esplicito se i riferimenti ARIA devono restare stabili tra i render.

### Modal

Finestra di dialogo con attributi ARIA e focus trap automatico (tramite il JavaScript di Bootstrap Italia). Il parametro `id` è **obbligatorio** per collegare il trigger `data-bs-target`.

```blade
<x-italia::button data-bs-toggle="modal" data-bs-target="#confirm-modal">
    Apri conferma
</x-italia::button>

<x-italia::modal
    id="confirm-modal"
    title="Conferma azione"
    description="Questa operazione non può essere annullata."
>
    Sei sicuro di voler procedere?

    <x-slot:footer>
        <x-italia::button variant="primary">Conferma</x-italia::button>
        <x-italia::button variant="outline" data-bs-dismiss="modal">Annulla</x-italia::button>
    </x-slot:footer>
</x-italia::modal>
```

Senza `id`, il componente genera un ID univoco (`dlk-modal-...`) che non può essere collegato a un trigger statico `data-bs-target`.

Gli esempi d'uso dei componenti sono nel [catalogo del playground](https://github.com/igorsmoleac/design-laravel-kit/blob/main/playground/resources/views/catalog.blade.php), usato per lo sviluppo.

## Accessibilità

I componenti includono link salta-contenuto, label associate ai campi, attributi ARIA per gli errori dei form e attributi `data-element` per i link legali del footer. Il riferimento normativo per l'accessibilità dei servizi digitali della PA è la [Legge 9 gennaio 2004, n. 4 (Legge Stanca)](https://www.normattiva.it/eli/id/2004/01/17/004G0015/CONSOLIDATED/20231118). WCAG 2.1 livello AA è il criterio di riferimento dichiarato; il pacchetto non certifica la conformità del sito o del servizio che lo integra.

<a id="limitazioni-note"></a>Limitazioni note:
- Il contrasto cromatico ereditato da Bootstrap Italia 2.18.3 può richiedere una verifica manuale: axe-core segnala alcuni nodi come controllo incompleto perché non riesce a determinare il colore di sfondo.
- Il focus trap del modal è gestito dal JavaScript di Bootstrap Italia e non è testato dal pacchetto.
- L'annuncio degli errori associati ai fieldset può variare con screen reader meno recenti.

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
