<x-layout :page="$page">
  <div class="container">
    <x-card>
      <form action="{{ route('projects.index') }}">
        <div class="input-group mb-3">
          <input type="text" class="form-control" placeholder="Name" name="name" value="{{ request('name') }}">
          <div class="input-group-append">
            <button class="btn btn-outline-secondary" type="submit"><i class="fa fa-search"></i> Търси</button>
          </div>
        </div>
      </form>
      <x-table :columns="['Име', 'Последна задача', 'Действия']">
        @foreach($projects as $project)
        <tr>
          <td>{{ $project->name }}</td>
          <td>{{ $project->tasks_updated_at }}</td>
          <td>
            <a href="{{ route('projects.show', $project->id) }}" class="btn btn-primary">
              <i class="fa fa-search"></i>
              Задачи
            </a>
            <a href="{{ route('projects.edit', $project->id) }}" class="btn btn-secondary">
              <i class="fa fa-edit"></i>
              Редактирай
            </a>
          </td>
        </tr>
        @endforeach
      </x-table>
      <x-paginate :model=$projects />
    </x-card>
  </div>
</x-layout>