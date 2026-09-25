@if ($hasItems())
    <div class="{{ $wrapperClass() }}" @if ($sticky) data-bs-toggle="sticky" @endif>
        <div class="container-xxl">
            <div class="row">
                <div class="col-12">
                    <nav class="{{ $navClass() }}" aria-label="{{ __('Menu principale') }}">
                        <button
                            class="custom-navbar-toggler"
                            type="button"
                            aria-controls="{{ $menuId() }}"
                            aria-expanded="false"
                            aria-label="{{ __('Apri il menu') }}"
                            data-bs-toggle="navbarcollapsible"
                            data-bs-target="#{{ $menuId() }}"
                        >
                            <x-italia::icon name="it-burger" />
                        </button>
                        <div class="navbar-collapsable" id="{{ $menuId() }}">
                            <div class="overlay"></div>
                            <div class="close-div">
                                <button class="btn close-menu" type="button">
                                    <x-italia::icon name="it-close" />
                                    <span>{{ __('Chiudi') }}</span>
                                </button>
                            </div>
                            <div class="menu-wrapper">
                                <ul class="navbar-nav">
                                    @foreach ($items as $item)
                                        @if (filled($item['megamenu'] ?? null))
                                            <li class="nav-item dropdown megamenu">
                                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <span>{{ $item['text'] ?? '' }}</span>
                                                    <x-italia::icon name="it-expand" />
                                                </a>
                                                <div class="dropdown-menu">
                                                    <div class="row">
                                                        @foreach ($item['megamenu'] as $section)
                                                            <div class="col-6 col-lg-4">
                                                                <div class="link-list-wrapper">
                                                                    @if (filled($section['heading'] ?? null))
                                                                        <div class="link-list-heading">{{ $section['heading'] }}</div>
                                                                    @endif
                                                                    <ul class="link-list">
                                                                        @foreach ($section['links'] ?? [] as $link)
                                                                            <li>
                                                                                <a class="dropdown-item list-item" href="{{ $link['url'] ?? '#' }}">{{ $link['text'] ?? '' }}</a>
                                                                            </li>
                                                                        @endforeach
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </li>
                                        @elseif (filled($item['dropdown'] ?? null))
                                            <li class="nav-item dropdown">
                                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <span>{{ $item['text'] ?? '' }}</span>
                                                    <x-italia::icon name="it-expand" />
                                                </a>
                                                <div class="dropdown-menu">
                                                    <div class="link-list-wrapper">
                                                        <ul class="link-list">
                                                            @foreach ($item['dropdown'] as $sub)
                                                                <li>
                                                                    <a class="dropdown-item list-item" href="{{ $sub['url'] ?? '#' }}">{{ $sub['text'] ?? '' }}</a>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                </div>
                                            </li>
                                        @else
                                            <li class="nav-item">
                                                <a
                                                    class="nav-link{{ $isItemActive($item) ? ' active' : '' }}"
                                                    href="{{ $item['url'] ?? '#' }}"
                                                    @if ($isItemActive($item)) aria-current="page" @endif
                                                ><span>{{ $item['text'] ?? '' }}</span></a>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </div>
@endif
