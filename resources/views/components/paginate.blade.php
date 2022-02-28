@props(['model' => false])
<div class="d-flex justify-content-center">
    @if($model && $model instanceof \Illuminate\Pagination\LengthAwarePaginator )
    {!! $model->appends(request()->except('page'))->links() !!}
    @endif
</div>