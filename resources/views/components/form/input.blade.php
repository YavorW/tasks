@props(['group' => true, 'size' => 12, 'label' => null, 'maxlength' => 255,
      'type'=>'text', 'iname' => null, 'name' => null, 'id' => null, 'required'=> false,  'error_block' => false,
])
@php
  if($iname) {
    $name = $id = $iname;
  }
  $id = changeId($id);
@endphp
@if($group)
<div class="form-group col-md-{{ $size }} @if($required)required @endif ">
@endif
  @if($label)
    <label for="{{ $id }}">{{ $label }}</label>
  @endif
  <input type="{{ $type }}" name="{{ $name }}" id="{{ $id }}" {{ $attributes->merge(['class' => 'form-control']) }}
    @if($required) required @endif  maxlength="{{ $maxlength }}">
  @error($name)
  <div class="text-danger">{{ $message }}</div>
  @enderror
  {{ $slot }}
  @if($error_block) {{ $error_block }} @endif
@if($group)
</div>
@endif