@props(['columns' => false])
<div class="table-responsive">
    <table {{ $attributes->merge(['class' => 'table bg-white']) }}>
        @if($columns)
        <tr>
            @foreach($columns as $column)
            <th>
                @if(strpos($column,'--sort'))
                @php
                preg_match('/(.*)--sort=(.*)/', $column, $matches);
                @endphp
                {{ $matches[1] }}
                <span class="text-nowrap">
                    <a href="{{ changeRouteParams(['order_by' => $matches[2], 'sort' => 'asc', 'page'=> 1]) }}"><i
                            class="fa fa-angle-up"></i></a>
                    <a href="{{ changeRouteParams(['order_by' => $matches[2], 'sort' => 'desc', 'page'=> 1]) }}"><i
                            class="fa fa-angle-down"></i></a>
                </span>
                @else
                {{ $column }}
                @endif
            </th>
            @endforeach
        </tr>
        @endif
        {{ $slot }}
    </table>
</div>