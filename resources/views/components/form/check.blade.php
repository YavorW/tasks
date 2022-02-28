@props(['group' => true, 'size' => 12, 'label' => null, 'checked' => false, 'newLine' => false,
'type' => 'radio', 'iname' => null, 'name' => null, 'id' => null, 'required'=> false, 'error_block' => false,
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
  @if($newLine)
  <div class="mb-3 d-sm-none d-md-block">&nbsp;</div>
  @endif
  <div class="form-check">
    <input type="hidden" name="{{ $name }}" value="">
    <input type="{{ $type }}" name="{{ $name }}" id="{{ $id }}" {{ $attributes->merge(['class' =>'form-check-input']) }}
      @if($required) required @endif 
      @if($checked) checked @endif
      >
    @if($label)
    <label for="{{ $id }}" class="form-check-label">{{ $label }}</label>
    @endif
  </div>
  @error($name)
  <div class="text-danger">{{ $message }}</div>
  @enderror
  {{ $slot }}
  @if($error_block) {{ $error_block }} @endif
  @if($group)
</div>
@endif