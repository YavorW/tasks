@props(['size' => 12, 'placeholder' => '', 'iname' => null, 'name' => null, 'id' => null, 'label' => '', 
'required'=> false, 'error_block' => false,])
@php
if($iname) {
$name = $id = $iname;
}
$id = changeId($id);
@endphp
<div class="form-group col-md-{{ $size }} @if($required)required @endif">
    <label for="{{ $id }}">{{ $label }}</label>
    <textarea name="{{ $name }}" id="{{ $id }}"
        {{ $attributes->merge(['class' => 'form-control ckeditor']) }} 
        @if($required) required @endif
        placeholder="{{ $placeholder }}"
    >{{ $slot }}</textarea>
    @error($name)
        <div class="text-danger">{{ $message }}</div>
    @enderror
    @if($error_block) {{ $error_block }} @endif
</div>


@once
@push('scripts')
<script src="{{ asset('plugins/ckeditor/ckeditor.js') }}"></script>
<script src="{{ asset('plugins/ckeditor/adapters/jquery.js') }}"></script>
@endpush
@endonce