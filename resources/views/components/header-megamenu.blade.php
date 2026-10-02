<li {{ $attributes->except('id')->class(['nav-item', 'dropdown', 'megamenu']) }}>
    <a
        class="nav-link dropdown-toggle{{ $active ? ' active' : '' }}"
        href="{{ $url ?: '#' }}"
        role="button"
        data-bs-toggle="dropdown"
        aria-expanded="false"
        aria-controls="{{ $id() }}"
        @if ($active) aria-current="page" @endif
    >
        <span>{{ $text }}</span>
        <x-italia::icon name="it-expand" />
    </a>
    <div class="dropdown-menu" id="{{ $id() }}">
        <div class="row">{{ $slot }}</div>
    </div>
</li>
