<!DOCTYPE html>
<html lang="{{ $htmlLang() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>{{ $pageTitle() }}</title>
    @if ($hasDescription())
        <meta name="description" content="{{ $description }}">
    @endif
    @designLaravelKitStyles
    @stack('styles')
</head>
<body class="{{ $bodyClasses() }}">
    @if ($skipToContent)
        <a class="visually-hidden-focusable" href="#main">{{ $skipLabel }}</a>
    @endif

    <x-italia::header
        :slim="$slim"
        :center="$center"
        :navbar="$navbar"
        :light="$light"
        :sticky="$sticky"
    />

    {{ $breadcrumbs ?? '' }}

    <main id="main" class="{{ $mainClass ?? 'container my-4' }}">
        {{ $slot }}
    </main>

    <x-italia::footer
        :title="$footer['title'] ?? config('app.name')"
        :subtitle="$footer['subtitle'] ?? null"
        :logo="$footer['logo'] ?? null"
        :logo-alt="$footer['logoAlt'] ?? null"
        :url="$footer['url'] ?? null"
        :sections="$footer['sections'] ?? []"
        :contacts="$footer['contacts'] ?? []"
        :social-links="$footer['socialLinks'] ?? []"
        :legal-links="$footer['legalLinks'] ?? []"
        :copyright="$footer['copyright'] ?? null"
        :light="$light"
    />

    @designLaravelKitScripts
    @stack('scripts')
</body>
</html>
