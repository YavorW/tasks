<x-layout :page="$page">
    <div class="mx-3">
        <x-card size=12>
            <x-table :columns="['Resource', 'Resource id', 'Action', 'User', 'Date', 'Message']">
                @foreach($logs as $log)
                <tr>
                    <td>
                        <a href="{{ route('admin.logs.index', ['type'=> $log->model_type]) }}">{{ $log->model_type
                            }}</a>
                    </td>
                    <td>
                        <a href="{{ route('admin.logs.index', ['type'=> $log->model_type, 'id'=> $log->model_id]) }}">{{
                            $log->model_id }}</a>
                    </td>
                    <td>{{ $log->method }}</td>
                    <td>
                        @if($log->user)
                        <a href="{{ route('admin.logs.index', ['user' => $log->user_id]) }}">{{ $log->user->email }}</a>
                        @endif
                    </td>
                    <td>{{ $log->created_at }}</td>
                    <td>
                        <pre><?php
                        $message = @unserialize($log->message);
                        if ($message) {
                            var_dump($message);
                        } else {
                            echo $log->message;
                        }
                    ?></pre>
                    </td>
                </tr>
                @endforeach
            </x-table>
            <div class="d-flex justify-content-center">
                {!! $logs->appends(request()->except('page'))->links() !!}
            </div>
        </x-card>
    </div>
    </x-admin>