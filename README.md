# design-laravel-kit

Modern Bootstrap Italia integration for Laravel. Blade components, Vite, accessibility.

> Work in progress.

## Performance

Il pacchetto pubblica il bundle completo di Bootstrap Italia (~1 MB, ~250-300 KB gzip).
Questo è il prezzo del zero-config: nessuna configurazione Vite richiesta.

Se hai bisogno di un bundle più piccolo, puoi importare solo i componenti necessari
nel tuo progetto Vite e non usare `@designLaravelKitScripts`:

    import { Collapse, Dropdown } from 'bootstrap-italia';
