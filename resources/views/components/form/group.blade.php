@props(['size' => 12, 'required'=> false])
@php
    $class = "form-group col-md-{$size} ";
    if($required) {
        $class .= 'required ';
    }
@endphp
<div {{ $attributes->merge(['class' => $class]) }}>
    {{ $slot }}
</div>