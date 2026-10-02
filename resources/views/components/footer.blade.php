<footer {{ $attributes->class([$wrapperClass()]) }}>
    <div class="it-footer-main">
        <div class="container">
            <section>
                <div class="row clearfix">
                    <div class="col-sm-12">
                        <div class="it-brand-wrapper">
                            <a href="{{ $brandUrl() }}">
                                @if ($hasLogo())
                                    <img class="icon" src="{{ $logo }}" alt="{{ $logoAlt ?? $title }}">
                                @else
                                    <x-italia::icon name="it-code-circle" />
                                @endif
                                <div class="it-brand-text">
                                    <h2 class="no_toc">{{ $title }}</h2>
                                    @if ($hasSubtitle())
                                        <h3 class="no_toc d-none d-md-block">{{ $subtitle }}</h3>
                                    @endif
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </section>

            @if (isset($sections) && trim((string) $sections) !== '')
                <section>
                    <div class="row">{{ $sections }}</div>
                </section>
            @endif

            @if (isset($contacts) && trim((string) $contacts) !== '')
                <section class="py-4 border-white border-top">
                    <div class="row">{{ $contacts }}</div>
                </section>
            @endif

            @if (isset($social) && trim((string) $social) !== '')
                <section class="py-4 border-white border-top">
                    <div class="row">
                        <div class="col-lg-4 col-md-4 pb-2">
                            <h4>{{ __('design-laravel-kit::Seguici su') }}</h4>
                            <ul class="list-inline text-left social">{{ $social }}</ul>
                        </div>
                    </div>
                </section>
            @endif
        </div>
    </div>

    @php($legalContent = $legalLinks ?? $slot)
    @if ($hasLegalLinks() || filled($copyrightText()))
        <div class="it-footer-small-prints clearfix">
            <div class="container">
                @if ($hasLegalLinks())
                    <h3 class="visually-hidden">{{ __('design-laravel-kit::Link utili') }}</h3>
                    <ul class="it-footer-small-prints-list list-inline mb-0 d-flex flex-column flex-md-row">
                        {{ $legalContent }}
                    </ul>
                @endif
                @if (filled($copyrightText()))
                    <p class="{{ $copyrightClass() }}">{{ $copyrightText() }}</p>
                @endif
            </div>
        </div>
    @endif
</footer>
