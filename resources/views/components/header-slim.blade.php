<div {{ $attributes->class([$wrapperClass()]) }} @if ($sticky) data-bs-toggle="sticky" @endif>
    <div class="container-xxl">
        <div class="row">
            <div class="col-12">
                <div class="it-header-slim-wrapper-content">
                    @if ($hasEnteLink())
                        <a class="d-none d-lg-block navbar-brand" href="{{ $enteUrl }}">{{ $ente }}</a>
                    @else
                        <span class="d-none d-lg-block navbar-brand">{{ $ente }}</span>
                    @endif
                    @if ($hasLinks())
                        <div class="nav-mobile">
                            <nav aria-label="{{ __('design-laravel-kit::Navigazione accessoria') }}">
                                <a class="it-opener d-lg-none" data-bs-toggle="collapse" href="#{{ $menuId() }}" role="button" aria-expanded="false" aria-controls="{{ $menuId() }}">
                                    <span>{{ $ente }}</span>
                                    <x-italia::icon name="it-expand" />
                                </a>
                                <div class="link-list-wrapper collapse" id="{{ $menuId() }}">
                                    <ul class="link-list">{{ $links ?? $slot }}</ul>
                                </div>
                            </nav>
                        </div>
                    @endif
                    @if ($hasLanguages() || $hasLogin())
                        <div class="it-header-slim-right-zone">
                            @if ($hasLanguages())
                                <div class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <span class="visually-hidden">{{ __('design-laravel-kit::Selezione lingua: lingua selezionata') }}</span>
                                        <span>{{ $languageLabel ?? '' }}</span>
                                        <x-italia::icon name="it-expand" class="d-none d-lg-block" />
                                    </a>
                                    <div class="dropdown-menu">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="link-list-wrapper">
                                                    <ul class="link-list">{{ $languages }}</ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if ($hasLogin())
                                <div class="it-access-top-wrapper">
                                    <x-italia::button href="{{ $loginUrl }}" variant="primary" size="sm">{{ $loginLabel }}</x-italia::button>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
