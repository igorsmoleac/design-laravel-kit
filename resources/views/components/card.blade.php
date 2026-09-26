<{{ $tagName() }} {{ $attributes->merge(['href' => $href])->class([$wrapperClass()]) }}>
    <div class="{{ $cardClass() }}">
        @if ($image !== null)
            <div class="img-responsive-wrapper">
                <div class="img-responsive">
                    <figure class="img-wrapper">
                        <img src="{{ $image }}" alt="{{ $imageAltText() }}">
                    </figure>
                </div>
            </div>
        @endif
        <div class="card-body">
            @if ($title !== null)
                <h3 class="card-title h5">{{ $title }}</h3>
            @endif
            @if ($subtitle !== null)
                <h6 class="card-subtitle">{{ $subtitle }}</h6>
            @endif
            <div class="card-text">{{ $slot }}</div>
            @isset($actions)
                <div class="mt-3">{{ $actions }}</div>
            @endisset
        </div>
    </div>
</{{ $tagName() }}>
