<footer class="{{ $wrapperClass() }}">
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

            @if ($hasSections())
                <section>
                    <div class="row">
                        @foreach ($sections as $section)
                            <div class="col-lg-3 col-md-3 col-sm-6 pb-2">
                                <h4>
                                    @if (filled($sectionUrl($section)))
                                        <a href="{{ $sectionUrl($section) }}">{{ $section['title'] ?? '' }}</a>
                                    @else
                                        {{ $section['title'] ?? '' }}
                                    @endif
                                </h4>
                                <div class="link-list-wrapper">
                                    <ul class="footer-list link-list clearfix">
                                        @foreach ($section['links'] ?? [] as $link)
                                            <li><a class="list-item" href="{{ $link['url'] ?? '#' }}">{{ $link['text'] ?? '' }}</a></li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif

            @if ($hasContacts() || $hasSocialLinks())
                <section class="py-4 border-white border-top">
                    <div class="row">
                        @if ($hasContacts())
                            <div class="col-lg-4 col-md-4 pb-2">
                                <h4>{{ __('Contatti') }}</h4>
                                @foreach ($contacts as $contact)
                                    @if ($isAddress($contact))
                                        <p>
                                            @if (filled($contact['label'] ?? null))
                                                <strong>{{ $contact['label'] }}</strong><br>
                                            @endif
                                            {{ $contact['value'] ?? '' }}
                                        </p>
                                    @endif
                                @endforeach
                                @if (filled($linkableContacts()))
                                    <div class="link-list-wrapper">
                                        <ul class="footer-list link-list clearfix">
                                            @foreach ($linkableContacts() as $contact)
                                                <li>
                                                    <a class="list-item" href="{{ $contactHref($contact) }}">
                                                        <x-italia::icon :name="$contactIcon($contact)" size="sm" />
                                                        {{ $contactLabel($contact) }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </div>
                        @endif
                        @if ($hasSocialLinks())
                            <div class="col-lg-4 col-md-4 pb-2">
                                <div class="pb-2">
                                    <h4>{{ __('Seguici su') }}</h4>
                                    <ul class="list-inline text-left social">
                                        @foreach ($socialLinks as $social)
                                            <li class="list-inline-item">
                                                <a
                                                    class="p-2 text-white"
                                                    href="{{ $social['url'] ?? '#' }}"
                                                    aria-label="{{ $social['label'] ?? '' }}"
                                                    target="_blank"
                                                    rel="noopener noreferrer"
                                                >
                                                    <x-italia::icon :name="$social['icon'] ?? 'it-link'" size="sm" class="icon-white align-top" />
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif
                    </div>
                </section>
            @endif
        </div>
    </div>

    @if ($hasLegalLinks() || filled($copyrightText()))
        <div class="it-footer-small-prints clearfix">
            <div class="container">
                @if ($hasLegalLinks())
                    <h3 class="visually-hidden">{{ __('Link utili') }}</h3>
                    <ul class="it-footer-small-prints-list list-inline mb-0 d-flex flex-column flex-md-row">
                        @foreach ($legalLinks as $link)
                            <li class="list-inline-item">
                                <a
                                    href="{{ $link['url'] ?? '#' }}"
                                    @if (filled($link['dataElement'] ?? null)) data-element="{{ $link['dataElement'] }}" @endif
                                >{{ $link['text'] ?? '' }}</a>
                            </li>
                        @endforeach
                    </ul>
                @endif
                @if (filled($copyrightText()))
                    <p class="{{ $copyrightClass() }}">{{ $copyrightText() }}</p>
                @endif
            </div>
        </div>
    @endif
</footer>
