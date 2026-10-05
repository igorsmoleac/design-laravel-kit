<div class="col-lg-3 col-md-3 col-sm-6 pb-2" {{ $attributes }}>
    <h4>
        @if ($url)
            <a href="{{ $url }}">{{ $title }}</a>
        @else
            {{ $title }}
        @endif
    </h4>
    <div class="link-list-wrapper">
        <ul class="footer-list link-list clearfix">{{ $slot }}</ul>
    </div>
</div>
