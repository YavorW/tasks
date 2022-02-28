@props(['group' => true, 'size' => 12, 'label' => null,
'iname' => null, 'name' => null, 'id' => null, 'required'=> false,  'error_block' => false,
])
@php
  if($iname) {
    $name = $id = $iname;
  }
  $id = changeId($id);
@endphp
@if($group)
<div class="form-group col-md-{{ $size }} @if($required) required @endif">
@endif
  @if($label)
  <label for="{{ $id }}">{{ $label }}</label>
  @endif
  <div class="custom-file mb-2">
    <input type="file" id="{{ $id }}" name="{{ $name }}" {{ $attributes->merge(['class' => 'custom-file-input']) }}
      @if($required) required @endif>
    <label class="custom-file-label" for="{{ $id }}">Избери файл</label>
  </div>

  @error($name)
  <div class="text-danger">{{ $message }}</div>
  @enderror
  {{ $slot }}
  @if($error_block) {{ $error_block }} @endif
@if($group)
</div>
@endif