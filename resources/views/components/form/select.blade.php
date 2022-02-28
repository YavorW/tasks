@props(['group' => true, 'size' => 12, 'label' => null, 'required'=> false, 'before_select' => false,
'iname' => null, 'name' => null, 'id' => null, 'placeholder' => null,  'error_block' => false,
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
  @if($before_select)
  {{ $before_select }}
  @endif
  <select name="{{ $name }}" id="{{ $id }}" {{ $attributes->merge(['class' => 'form-control ']) }} 
    @if($placeholder) data-placeholder="{{ $placeholder }}" @endif
    @if($required) required @endif
    >
    {{ $slot }}
  </select>
  @error($name)
  <div class="text-danger">{{ $message }}</div>
  @enderror
  @if($error_block) {{ $error_block }} @endif
@if($group)
</div>
@endif
