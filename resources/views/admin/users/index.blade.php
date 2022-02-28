<x-layout :page="$page">
    <div class="container">
        <x-card size=12>
            <form action="{{ route('admin.users.index') }}">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="Име или Email" name="name"
                        value="{{ request('name') }}">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="submit">Търси</button>
                    </div>
                </div>
            </form>
            <x-table :columns="['Id' , 'Име', 'Email', 'Роля', 'Действия']">
                @foreach($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->role }}</td>
                    <td>
                        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-primary">
                            <i class="fa fa-edit"></i>
                            Редактирай
                        </a>
                    </td>
                </tr>
                @endforeach
            </x-table>
            <x-paginate :model=$users />
        </x-card>
    </div>
</x-layout>