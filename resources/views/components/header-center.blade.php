<div id="{{ $id() }}" class="{{ $wrapperClass() }}">
    <div class="container-xxl">
        <div class="row">
            <div class="col-12">
                <div class="it-header-center-content-wrapper">
                    <div class="it-brand-wrapper">
                        <a href="{{ $brandUrl() }}">
                            @if ($hasLogo())
                                <img class="icon" src="{{ $logo }}" alt="{{ $logoAlt ?? $title }}">
                            @else
                                <x-italia::icon name="it-code-circle" size="xl" />
                            @endif
                            <div class="it-brand-text">
                                <div class="it-brand-title">{{ $title }}</div>
                                @if ($hasTagline())
                                    <div class="it-brand-tagline d-none d-md-block">{{ $tagline }}</div>
                                @endif
                            </div>
                        </a>
                    </div>

                    @if ($hasSocialLinks() || $hasSearch())
                        <div class="it-right-zone">
                            @if ($hasSocialLinks())
                                <div class="it-socials d-none d-md-flex">
                                    <span>{{ __('Seguici su') }}</span>
                                    <ul>
                                        @foreach ($socialLinks as $social)
                                            <li>
                                                <a href="{{ $social['url'] ?? '#' }}" aria-label="{{ $social['label'] ?? '' }}" target="_blank" rel="noopener noreferrer">
                                                    <x-italia::icon :name="$social['icon'] ?? 'it-link'" />
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            @if ($hasSearch())
                                <div class="it-search-wrapper">
                                    <span class="d-none d-md-block">{{ __('Cerca') }}</span>
                                    <a class="search-link rounded-icon" href="{{ $searchUrl }}" aria-label="{{ __('Cerca') }}">
                                        <x-italia::icon name="it-search" />
                                    </a>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
