<?php
use App\Models\Project;
?>
<x-layout :page=$page>
  <div class="container">
    <script>
      function teams() {
        return {
          team_manager: @json(isset($teams[Project::team_manager]) ? $teams[Project::team_manager] : [] ),
          team_qa: @json(isset($teams[Project::team_qa]) ? $teams[Project::team_qa] : [] ),
          team_backend: @json(isset($teams[Project::team_backend]) ? $teams[Project::team_backend] : [] ),
          team_frontend: @json(isset($teams[Project::team_frontend]) ? $teams[Project::team_frontend] : [] ),
          team_design: @json(isset($teams[Project::team_design]) ? $teams[Project::team_design] : [] ),
          team_support: @json(isset($teams[Project::team_support]) ? $teams[Project::team_support] : [] ),
        }
      }
    </script>
    <div class="row" x-data="teams()">
      <div class="col-md-8">
        <x-form :action="$action" :method="$method" size=12>
          <x-form.input iname="name" label="Име на проекта" placeholder="име" size=6 required
            value="{{ old('name', isset($project) ? $project->name : '') }}" />

          <x-form.input iname="link" label="Адрес проекта" placeholder="https://..." size=6
            value="{{ old('link', isset($project) ? $project->link : '') }}" />

          <x-form.textarea iname="description" label="Описание (дизайни, акаунти, настройки)">{{ isset($project) ?
            $project->description : ''}}</x-form.textarea>


          <h2>Екипи</h2>
          <x-form.select2 iname="teams[{{ Project::team_manager }}][]" label="Менижъри" multiple x-model="team_manager"
            size=6 class="d-none" placeholder="Избери Менижър">
            @foreach($users as $user)
            <option value="{{ $user->id }}">{{ $user->name }}</option>
            @endforeach
          </x-form.select2>

          <x-form.select2 iname="teams[{{ Project::team_qa }}][]" label="QA" multiple x-model="team_qa" size=6
            class="d-none" placeholder="Избери QA">
            @foreach($users as $user)
            <option value="{{ $user->id }}">{{ $user->name }}</option>
            @endforeach

          </x-form.select2>
          <x-form.select2 iname="teams[{{ Project::team_backend }}][]" label="Back-End" multiple x-model="team_backend"
            size=6 class="d-none" placeholder="Избери Back-End Програмисти">
            @foreach($users as $user)
            <option value="{{ $user->id }}">{{ $user->name }}</option>
            @endforeach
          </x-form.select2>

          <x-form.select2 iname="teams[{{ Project::team_frontend }}][]" label="Front-End" multiple
            x-model="team_frontend" size=6 class="d-none" placeholder="Избери Front-End Програмисти">
            @foreach($users as $user)
            <option value="{{ $user->id }}">{{ $user->name }}</option>
            @endforeach
          </x-form.select2>

          <x-form.select2 iname="teams[{{ Project::team_design }}][]" label="Дизайн" multiple x-model="team_design"
            size=6 class="d-none" placeholder="Избери Дизайнери">
            @foreach($users as $user)
            <option value="{{ $user->id }}">{{ $user->name }}</option>
            @endforeach
          </x-form.select2>
          <x-form.select2 iname="teams[{{ Project::team_support }}][]" label="Поддръжка" multiple x-model="team_support"
            size=6 class="d-none" placeholder="Избери Поддръжка">
            @foreach($users as $user)
            <option value="{{ $user->id }}">{{ $user->name }}</option>
            @endforeach
          </x-form.select2>
        </x-form>
      </div>
    </div>
  </div>
</x-layout>