<?php
use App\Models\Project;
use App\Models\Task;
?>
<!-- create modal -->
<script>
  function createModal() {
    return {
      subject: null,
      description: null,
      steps_to_reproduce: null,
      type: {{ Task::type_bug }},
      priority: {{ Task::priority_medium }},
      team: null,
      user_id: null,
      files: {
        task: [],
        description: [],
        steps: []
      },
      init() {
        // качване на снимка от клипборда
        $('.pastejs.create').pastableTextarea();

        $('.pastejs.create').on('pasteImage', function(ev, data) {
          const type = $(this).data('type');
          @this.pasteImage(data.dataURL, type, 'create');

        }).on('pasteImageError', function(ev, data) {
          alert('Oops: ' + data.message);
          if (data.url) {
            alert('But we got its url anyway:' + data.url)
          }
        });
      },
      reset() {
        this.subject = null;
        this.description = null;
        this.steps_to_reproduce = null;
        this.type = {{ Task::type_bug }};
        this.priority = {{ Task::priority_medium }};
        this.team = null;
        this.user_id = null;
        this.files = {
          task: [],
          description: [],
          steps: []
        };
        this.$refs.task_files.innerHtml = '';
        this.$refs.description_files.innerHtml = '';
        this.$refs.steps_files.innerHtml = '';
      },
      pasteImage(type, uri) {
        this.files[type].push(uri);
      },
      remove(type, index) {
        this.files[type] = this.files[type].filter((el, i) => i != index);
      },

      save() {
        @this.store(
          this.subject,
          this.description,
          this.steps_to_reproduce,
          this.type,
          this.priority,
          this.team,
          this.user_id,
          this.files
        );
        this.reset();
      }
    }
  }
</script>
<div class="modal fade" id="create-modal" tabindex="-1" data-bs-backdrop="static" x-data="createModal()"
  x-init="init"
  x-on:paste-image.window="if($event.detail.modal == 'create') pasteImage($event.detail.type, $event.detail.uri)">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Добави задача</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="container-fluid">
          <div class="row">
            <x-form.input class="pastejs create" label="Задача" placeholder="Задача" x-model="subject" required
              data-type="task" />

            <x-form.group class="my-2">
              <div>
                <label>Снимки задача <span class="text-black-50">(двоен клик за изтриване)</span></label>
              </div>
              <div id="task_files" ref="task_files">
                <template x-for="(uri, index) in files.task" :key="'task-' + index">
                  <img x-bind:src="'{{ getProjectUrl('') }}' + uri" x-on:dblclick="remove('task', index)"
                    class="max-235 img-thumbnail m-1">
                </template>
              </div>
            </x-form.group>

            <x-form.textarea class="pastejs create" label="Описание" placeholder="Описание" x-model="description"
              data-type="description">
            </x-form.textarea>

            <x-form.group class="my-2">
              <div>
                <label>Снимки описание <span class="text-black-50">(двоен клик за изтриване)</span></label>
              </div>
              <div id="description_files" ref="description_files">

                <template x-for="(uri, index) in files.description" :key="'description-' + index">
                  <img x-bind:src="'{{ getProjectUrl('') }}' + uri" x-on:dblclick="remove('description', index)"
                    class="max-235 img-thumbnail m-1">
                </template>
              </div>
            </x-form.group>

            <x-form.textarea class="pastejs create" label="Стъпки за пресъздаване" placeholder="Стъпки за пресъздаване"
              x-model="steps_to_reproduce" data-type="steps">
            </x-form.textarea>

            <x-form.group class="my-2">
              <div>
                <label>Снимки пресъздаване <span class="text-black-50">(двоен клик за изтриване)</span></label>
              </div>
              <div id="steps_files" ref="steps_files">
                <template x-for="(uri, index) in files.steps" :key="'steps-' + index">
                  <img x-bind:src="'{{ getProjectUrl('') }}' + uri" x-on:dblclick="remove('steps', index)"
                    class="max-235 img-thumbnail m-1">
                </template>
              </div>
            </x-form.group>
            <x-form.select x-model="type" label="Тип" size=6>
              <option value="">Избери</option>
              <option value="{{ Task::type_bug }}">Bug</option>
              <option value="{{ Task::type_new_feature }}">New Feature</option>
              <option value="{{ Task::type_change }}">Change</option>
            </x-form.select>

            <x-form.select x-model="priority" label="Приоритет" size=6>
              <option value="">Избери</option>
              <option value="{{ Task::priority_lowest }}">Lowest</option>
              <option value="{{ Task::priority_low }}">Low</option>
              <option value="{{ Task::priority_medium }}">Medium</option>
              <option value="{{ Task::priority_high }}">High</option>
              <option value="{{ Task::priority_highest }}">Highest</option>
            </x-form.select>

            <x-form.select x-model="team" label="Екип" size=6>
              <option value="">Избери</option>
              <option value="{{ Project::team_manager }}">Менижъри</option>
              <option value="{{ Project::team_backend }}">Back-End</option>
              <option value="{{ Project::team_frontend }}">Front-End</option>
              <option value="{{ Project::team_design }}">Дизайн</option>
              <option value="{{ Project::team_support }}">Поддръжка</option>
            </x-form.select>

            <x-form.select x-model="user_id" label="Изпълнител" size=6>
              <option value="">Избери</option>
              @if ($users)
                @foreach ($users as $user)
                  <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
              @endif
            </x-form.select>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" x-on:click="save()" data-bs-dismiss="modal">Запази</button>
      </div>
    </div>
  </div>
</div>
<!-- /create modal -->
