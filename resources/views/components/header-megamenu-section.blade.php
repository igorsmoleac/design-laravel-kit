<div {{ $attributes->class(['col-12', 'col-lg-4']) }}>
    <div class="link-list-wrapper">
        <h3 class="link-list-heading">{{ $heading }}</h3>
        <ul class="link-list">{{ $slot }}</ul>
    </div>
</div>
