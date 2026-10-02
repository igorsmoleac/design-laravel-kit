<!DOCTYPE html>
<html lang="{{ $htmlLang() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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

    <x-italia::header :light="$light" :sticky="$sticky">
        @if ($slimConfig)
            <x-slot:slim
                :ente="$slimConfig->ente"
                :ente-url="$slimConfig->enteUrl"
                :login-url="$slimConfig->loginUrl"
                :login-label="$slimConfig->loginLabel"
                :light="$light || $slimConfig->light"
                :sticky="$slimConfig->sticky"
            >{{ $slim }}</x-slot:slim>
        @endif
        @if ($centerConfig)
            <x-slot:center
                :title="$centerConfig->title"
                :tagline="$centerConfig->tagline"
                :logo="$centerConfig->logo"
                :logo-alt="$centerConfig->logoAlt"
                :url="$centerConfig->url"
                :search-url="$centerConfig->searchUrl"
                :small="$centerConfig->small"
                :light="$light || $centerConfig->light"
            >{{ $center }}</x-slot:center>
        @endif
        @if ($navbarConfig)
            <x-slot:navbar
                :light="$light || $navbarConfig->light"
                :sticky="$navbarConfig->sticky"
            >{{ $navbar }}</x-slot:navbar>
        @endif
    </x-italia::header>

    {{ $breadcrumbs ?? '' }}

    <main id="main" class="{{ $mainClass ?? 'container my-4' }}">
        {{ $slot }}
    </main>

    <x-italia::footer
        :title="$footerConfig->title"
        :subtitle="$footerConfig->subtitle"
        :logo="$footerConfig->logo"
        :logo-alt="$footerConfig->logoAlt"
        :url="$footerConfig->url"
        :copyright="$footerConfig->copyright"
        :light="$light || $footerConfig->light"
    >
        @isset($footerSections)
            <x-slot:sections>{{ $footerSections }}</x-slot:sections>
        @endisset
        @isset($footerContacts)
            <x-slot:contacts>{{ $footerContacts }}</x-slot:contacts>
        @endisset
        @isset($footerSocial)
            <x-slot:social>{{ $footerSocial }}</x-slot:social>
        @endisset
        @isset($footerLegalLinks)
            <x-slot:legal-links>{{ $footerLegalLinks }}</x-slot:legal-links>
        @endisset
    </x-italia::footer>

    @designLaravelKitScripts
    @stack('scripts')
</body>
</html>
