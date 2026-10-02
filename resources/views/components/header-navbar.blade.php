@if ($hasItems())
    <div {{ $attributes->class([$wrapperClass()]) }} @if ($sticky) data-bs-toggle="sticky" @endif>
        <div class="container-xxl">
            <div class="row">
                <div class="col-12">
                    <nav class="{{ $navClass() }}" aria-label="{{ __('design-laravel-kit::Menu principale') }}">
                        <button
                            class="custom-navbar-toggler"
                            type="button"
                            aria-controls="{{ $menuId() }}"
                            aria-expanded="false"
                            aria-label="{{ __('design-laravel-kit::Apri il menu') }}"
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
                                    <span>{{ __('design-laravel-kit::Chiudi') }}</span>
                                </button>
                            </div>
                            <div class="menu-wrapper">
                                <ul class="navbar-nav">{{ $slot }}</ul>
                            </div>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </div>
@endif
