<div {{ $attributes->class([$wrapperClass()]) }}>
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
                <h3 class="card-title h5">
                    @if ($href !== null)
                        <a href="{{ $href }}" class="stretched-link">{{ $title }}</a>
                    @else
                        {{ $title }}
                    @endif
                </h3>
            @endif
            @if ($subtitle !== null)
                <h6 class="card-subtitle">{{ $subtitle }}</h6>
            @endif
            <div class="card-text">{{ $slot }}</div>
            @isset($actions)
                <div class="card-actions mt-3 position-relative">{{ $actions }}</div>
            @endisset
        </div>
    </div>
</div>
