@props(['size' => 12, 'iname' => null, 'name' => null, 'id' => null, 'label' => '', 
'required'=> false, 'checked' => false, 'emptyLabel'=> false,  'error_block' => false,])

@php
  if($iname) {
    $name = $id = $iname;
  }
  $id = changeId($id);
@endphp
<div class="form-group col-md-{{ $size }} @if($required)required @endif">
    @if($emptyLabel)
    <label>&nbsp;</label>
    @endif
    <div class="custom-control custom-switch">
        <input type="hidden" name="{{ $name }}" value="">
        <input type="checkbox" name="{{ $name }}" id="{{ $id }}"
            {{ $attributes->merge(['class' => 'custom-control-input']) }} 
            @if($required) required @endif 
            @if($checked) checked @endif>
        <label class="custom-control-label" for="{{ $id }}">{{ $label }}</label>
    </div>
    @error($name)
    <div class="text-danger">{{ $message }}</div>
    @enderror
    {{ $slot }}
    @if($error_block) {{ $error_block }} @endif
</div>