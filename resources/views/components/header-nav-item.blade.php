@if ($dropdown)
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="{{ $config()->url }}" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <span>{{ $config()->text }}</span>
            <x-italia::icon name="it-expand" />
        </a>
        <div class="dropdown-menu">
            <div class="link-list-wrapper">
                <ul class="link-list">{{ $slot }}</ul>
            </div>
        </div>
    </li>
@else
    <li class="nav-item">
        <a
            class="nav-link{{ $config()->active ? ' active' : '' }}"
            href="{{ $config()->url }}"
            @if ($config()->active) aria-current="page" @endif
        ><span>{{ $config()->text }}</span></a>
    </li>
@endif
