@props(['type' => 'primary', 'title' => '', 'size'=> 8])
<div {{ $attributes->merge(['class' => "card card-$type card-outline col-md-$size"]) }}>
    <div class="card-body">
        @if($title != '')
        <h5 class="card-title mb-3">{!! $title !!}</h5>
        @endif
        <div class="card-text">
            {!! $slot !!}
        </div>
    </div>
</div>