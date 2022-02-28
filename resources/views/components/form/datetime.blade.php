@props(['group' => true, 'size' => 12, 'label' => null,
'iname' => null, 'name' => null, 'id' => null, 'required'=> false, 'error_block' => false,
])
@php
  if($iname) {
    $name = $id = $iname;
  }
  $id = changeId($id);
@endphp
@if($group)
<div class="form-group col-md-{{ $size }} @if($required)required @endif">
@endif
  @if($label)
  <label for="{{ $id }}">{{ $label }}</label>
  @endif

  <div class="input-group date">
    <input type="text" name="{{ $name }}" id="{{ $id }}"
      pattern="[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1]) (2[0-3]|[01][0-9]):[0-5][0-9]"
      {{ $attributes->merge(['class' => 'form-control  datetimepicker-input']) }} @if($required) required @endif>
    <div class="input-group-append">
      <div class="input-group-text"><i class="fa fa-calendar"></i></div>
    </div>
  </div>
  @error($name)
  <div class="text-danger">{{ $message }}</div>
  @enderror
  {{ $slot }}
  @if($error_block) {{ $error_block }} @endif
@if($group)
</div>
@endif

@once
@push('styles')
<!-- daterange picker -->
<link rel="stylesheet" href="{{ asset('/plugins/daterangepicker/daterangepicker.css') }}">
@endpush
@push('scripts')
<!-- daterange picker -->
<script src="{{ asset('/plugins/moment/moment.min.js') }}"></script>
<script src="{{ asset('/plugins/daterangepicker/daterangepicker.js') }}"></script>

<script>
  $(function() {$('.datetimepicker-input').daterangepicker({
      timePicker: true,
      timePicker24Hour: true,
      singleDatePicker: true,
      autoApply: true,
      drops: "auto",
      locale: {
        format: 'YYYY-MM-DD HH:mm'
      }
    });
  });
</script>
@endpush
@endonce