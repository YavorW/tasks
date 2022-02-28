<x-layout :page="$page">
    <div class="container">
        <x-card>
            <x-table :columns="['Key' , 'Value', 'Actions']">
                @foreach($settings as $setting)
                <tr>
                    <td>{{ $setting->key }}</td>
                    <td>{{ $setting->value }}</td>
                    <td>
                        <a href="{{ route('admin.settings.edit', $setting->id) }}" class="btn btn-primary">
                            <i class="fa fa-edit"></i>
                            Редактирай
                        </a>
                    </td>
                </tr>
                @endforeach
            </x-table>
            <x-paginate :model=$settings />
        </x-card>
    </div>
</x-layout>