@props(['group' => true, 'size' => 12, 'label' => null,
'iname' => null, 'name' => null, 'id' => null, 'required'=> false, 'error_block' => false,])
@php
if($iname) {
$name = $id = $iname;
}
$id = changeId($id);
@endphp
@if($group)
<div class="form-group col-md-{{ $size }} @if($required)required @endif px-0">
  @endif
  @if($label)
  <label for="{{ $id }}">{{ $label }}</label>
  @endif
  <textarea name="{{ $name }}" id="{{ $id }}" {{ $attributes->merge(['class' => 'editor']) }}
    @if($required) required @endif>{{ $slot }}</textarea>
  @error($name)
  <div class="text-danger">{{ $message }}</div>
  @enderror
  @if($error_block) {{ $error_block }} @endif
  @if($group)
</div>
@endif

@once
@push('styles')
<!-- summernote -->
<link rel="stylesheet" href="{{ asset('/plugins/summernote/summernote-bs4.min.css') }}">
<style>
  /* .note-editor.note-airframe .note-editing-area .note-editable, .note-editor.note-frame .note-editing-area .note-editable { min-height: 500px; } */
</style>
@endpush
@push('scripts')
<!-- summernote -->
<script src="{{ asset('/plugins/summernote/summernote-bs4.min.js') }}"></script>

<script>
  $(function() {
    // Summernote
    $('.editor').summernote({
      height: 500,
      callbacks: {
        onImageUpload: function(files) {
          uploadImage(files);
        }
      }
    });

    function uploadImage(files) {
      var data = new FormData();
      data.append("img", files[0]);
      data.append("alt", null);
      data.append("ajax", 1);
      $.ajax({
        url: "{{ route('admin.media.store') }}",
        cache: false,
        contentType: false,
        processData: false,
        data: data,
        type: "post",
        success: function(response) {
            var image = $('<img>').attr('src',response.img).attr('alt',response.alt);
            $('.editor').summernote("insertNode", image[0]);
        },
        error: function(data) {
          alert('Има проблем'); 
          console.log(data);
        }
      });
    }
    //
  });
</script>
@endpush
@endonce