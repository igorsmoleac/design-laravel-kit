{{-- SPID logo: official asset from italia/spid-sp-access-button (AgID), EUPL-1.2 repo --}}
@if ($hasDropdown())
    <div class="dropdown" {{ $attributes }}>
        <button type="button" class="{{ $cssClass() }}" data-bs-toggle="dropdown" aria-expanded="false" aria-haspopup="true">
            <svg class="dlk-spid-logo" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 587.6 587.6" aria-hidden="true" focusable="false">
                <path fill="#fff" d="M587.6 293.8c0 162.3-131.5 293.8-293.8 293.8C131.6 587.6 0 456.1 0 293.8S131.6 0 293.8 0c162.3 0 293.8 131.5 293.8 293.8"/>
                <path fill="#06c" d="M294.6 319c-24.4 0-44.5-8.2-60.3-24.8-15.8-16.5-23.7-37-23.7-61.4 0-24.5 7.9-44.8 23.6-61 15.7-16.2 35.7-24.3 60.2-24.3 24.4 0 44.3 8.2 59.6 24.9 15.3 16.6 23 37 23 61.5 0 24.3-7.7 44.6-23 60.8-15.3 16.1-35 24.3-59.4 24.3"/>
                <path fill="#06c" d="M210.6 439.1c0-24.5 7.9-44.8 23.5-61 15.7-16.2 35.7-24.3 60.4-24.3 24.4 0 44.3 8.2 59.5 24.9 15.3 16.7 23 37.1 23 61.5"/>
            </svg>
            <span class="dlk-spid-label">{{ $label }}</span>
        </button>
        <ul class="dropdown-menu" aria-label="Identity Provider SPID">
            @foreach ($providers as $provider)
                <li><a class="dropdown-item" href="{{ $provider['url'] }}">{{ $provider['name'] }}</a></li>
            @endforeach
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="https://www.spid.gov.it">Maggiori informazioni</a></li>
            <li><a class="dropdown-item" href="https://www.spid.gov.it/richiedi-spid">Non hai SPID?</a></li>
            <li><a class="dropdown-item" href="https://www.spid.gov.it/serve-aiuto">Serve aiuto?</a></li>
        </ul>
    </div>
@else
    <a href="{{ $loginUrl() }}" {{ $attributes->class([$cssClass()]) }} aria-label="{{ $label }}">
        <svg class="dlk-spid-logo" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 587.6 587.6" aria-hidden="true" focusable="false">
            <path fill="#fff" d="M587.6 293.8c0 162.3-131.5 293.8-293.8 293.8C131.6 587.6 0 456.1 0 293.8S131.6 0 293.8 0c162.3 0 293.8 131.5 293.8 293.8"/>
            <path fill="#06c" d="M294.6 319c-24.4 0-44.5-8.2-60.3-24.8-15.8-16.5-23.7-37-23.7-61.4 0-24.5 7.9-44.8 23.6-61 15.7-16.2 35.7-24.3 60.2-24.3 24.4 0 44.3 8.2 59.6 24.9 15.3 16.6 23 37 23 61.5 0 24.3-7.7 44.6-23 60.8-15.3 16.1-35 24.3-59.4 24.3"/>
            <path fill="#06c" d="M210.6 439.1c0-24.5 7.9-44.8 23.5-61 15.7-16.2 35.7-24.3 60.4-24.3 24.4 0 44.3 8.2 59.5 24.9 15.3 16.7 23 37.1 23 61.5"/>
        </svg>
        <span class="dlk-spid-label">{{ $label }}</span>
    </a>
@endif
