<header {{ $attributes->class([$wrapperClass()]) }} @if ($sticky) data-bs-toggle="sticky" @endif>
    @if ($slimConfig)
        <x-italia::header-slim
            :ente="$slimConfig->ente"
            :ente-url="$slimConfig->enteUrl"
            :login-url="$slimConfig->loginUrl"
            :login-label="$slimConfig->loginLabel"
            :light="$light || $slimConfig->light"
            :sticky="false"
        >{{ $slim ?? '' }}</x-italia::header-slim>
    @endif

    @if ($centerConfig || $navbarConfig)
        <div class="it-nav-wrapper">
            @if ($centerConfig)
                <x-italia::header-center
                    :title="$centerConfig->title"
                    :tagline="$centerConfig->tagline"
                    :logo="$centerConfig->logo"
                    :logo-alt="$centerConfig->logoAlt"
                    :url="$centerConfig->url"
                    :search-url="$centerConfig->searchUrl"
                    :small="$small || $centerConfig->small"
                    :light="$light || $centerConfig->light"
                >{{ $center ?? '' }}</x-italia::header-center>
            @endif

            @if ($navbarConfig)
                <x-italia::header-navbar
                    :light="$light || $navbarConfig->light"
                    :sticky="false"
                >{{ $navbar ?? '' }}</x-italia::header-navbar>
            @endif
        </div>
    @endif
</header>
