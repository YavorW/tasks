@props(['method' => 'post', 'action' => null, 'submit' => 'Запази', 'size' => 8, 'card' => true, 'inline' => false])
<div class="col-md-{{ $size }} px-0">
  <form method="{{ ($method == 'get') ? 'get' : 'post' }}" action="{{ $action }}" @if( in_array(strtolower($method), ['post','put','patch']))
    enctype="multipart/form-data" @endif {{ $attributes->merge(['class' => ($inline) ? 'form-inline' : '']) }}>
    @csrf
    @if($method != 'post' || $method != 'get')
      @method($method)
    @endif
    @if($card)
    <div class="card px-2">
      <div class="card-body row">
        {{ $slot }}
      </div>
    </div>
    @else
      {{ $slot }}
    @endif
    <div class="mt-3">
      <x-form.submit :action="$submit" />
    </div>
  </form>
</div>