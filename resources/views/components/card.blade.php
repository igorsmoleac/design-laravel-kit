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
                <{{ $headingTag() }} class="card-title h5">
                    @if ($href !== null)
                        <a href="{{ $href }}" class="stretched-link">{{ $title }}</a>
                    @else
                        {{ $title }}
                    @endif
                </{{ $headingTag() }}>
            @endif
            @if ($subtitle !== null)
                <p class="card-subtitle">{{ $subtitle }}</p>
            @endif
            <div class="card-text">{{ $slot }}</div>
            @isset($actions)
                <div class="card-actions mt-3 position-relative">{{ $actions }}</div>
            @endisset
        </div>
    </div>
</div>
