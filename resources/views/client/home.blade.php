<x-layout :page="$page">
  <div class="container">
    @foreach ($projects as $project)
      <h2>
        <a href="{{ route('projects.show', ['project' => $project->id]) }}"
          class="text-decoration-none text-black">{{ $project->name }}
        </a>
      </h2>
      <div class="row">
        @foreach ($tasks[$project->id] as $type)
          @foreach ($type as $task)
            <div class="col-md-3 mb-3">
              <a href="{{ route('projects.show', ['project' => $project->id, 'task' => $task->id]) }}"
                class="text-decoration-none text-black">
                <x-card size=12>
                  #{{ $task->id }} - {{ $task->subject }}
                </x-card>
              </a>
            </div>
          @endforeach
        @endforeach
      </div>
    @endforeach
  </div>
</x-layout>
