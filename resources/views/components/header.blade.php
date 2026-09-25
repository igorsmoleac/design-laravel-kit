<header class="{{ $wrapperClass() }}" @if ($sticky) data-bs-toggle="sticky" @endif>
    @if ($hasSlim())
        <x-italia::header-slim
            :ente="$slim['ente'] ?? 'Ente appartenenza'"
            :ente-url="$slim['enteUrl'] ?? null"
            :links="$slim['links'] ?? []"
            :languages="$slim['languages'] ?? []"
            :login-url="$slim['loginUrl'] ?? null"
            :login-label="$slim['loginLabel'] ?? 'Accedi'"
            :light="$light"
            :sticky="false"
        />
    @endif

    @if ($hasCenter() || $hasNavbar())
        <div class="it-nav-wrapper">
            @if ($hasCenter())
                <x-italia::header-center
                    :title="$center['title'] ?? ''"
                    :tagline="$center['tagline'] ?? null"
                    :logo="$center['logo'] ?? null"
                    :logo-alt="$center['logoAlt'] ?? null"
                    :url="$center['url'] ?? null"
                    :social-links="$center['socialLinks'] ?? []"
                    :search-url="$center['searchUrl'] ?? null"
                    :small="$small || (bool) ($center['small'] ?? false)"
                    :light="$light"
                />
            @endif

            @if ($hasNavbar())
                <x-italia::header-navbar
                    :items="$navbar['items'] ?? []"
                    :light="$light"
                    :sticky="false"
                />
            @endif
        </div>
    @endif
</header>
