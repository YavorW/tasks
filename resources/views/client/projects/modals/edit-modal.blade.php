<?php
use App\Models\Project;
use App\Models\Task;
?>
<!-- edit modal -->
<script>
  function editModal() {
    return {
      subject: null,
      description: null,
      steps_to_reproduce: null,
      type: null,
      priority: null,
      status: null,
      team: null,
      user_id: null,
      files: {
        task: [],
        description: [],
        steps: []
      },
      history: null,
      comments: null,
      init() {
        // качване на снимка от клипборда
        $('.pastejs.edit').pastableTextarea();

        $('.pastejs.edit').on('pasteImage', function(ev, data) {
          const type = $(this).data('type');
          @this.pasteImage(data.dataURL, type, 'edit');

        }).on('pasteImageError', function(ev, data) {
          alert('Oops: ' + data.message);
          if (data.url) {
            alert('But we got its url anyway:' + data.url)
          }
        });
      },
      loadTask(task) {
        const edit_modal = new bootstrap.Modal(document.getElementById('edit-modal'));

        this.id = task.id;
        this.subject = task.subject;
        this.description = task.description;
        this.steps_to_reproduce = task.steps_to_reproduce;
        this.type = task.type;
        this.priority = task.priority;
        this.status = task.status;
        this.team = task.team;
        this.user_id = task.user_id;
        this.files = task.files;
        this.history = task.history;
        this.comments = task.comments;

        edit_modal.show();
      },
      getData() {
        return {
          id: this.id,
          subject: this.subject,
          description: this.description,
          steps_to_reproduce: this.steps_to_reproduce,
          type: this.type,
          priority: this.priority,
          status: this.status,
          team: this.team,
          user_id: this.user_id,
          files: this.files
        }
      },

      pasteImage(type, uri) {
        this.files[type].push(uri);
      },
      remove(type, index) {
        this.files[type] = this.files[type].filter((el, i) => i != index);
      },

      save() {
        @this.taskUpdate(this.id, this.getData());
      }
    }
  }
</script>
<div class="modal fade" id="edit-modal" tabindex="-1" data-bs-backdrop="static" x-data="editModal()"
  x-on:load-task.window="loadTask($event.detail)" x-init="init"
  x-on:paste-image.window="if($event.detail.modal == 'edit') pasteImage($event.detail.type, $event.detail.uri)">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Редактирай задача</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-6">
              <div class="row">
                <x-form.input class="pastejs edit" label="Задача" placeholder="Задача" x-model="subject" required
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

                <x-form.textarea class="pastejs edit" label="Описание" placeholder="Описание" x-model="description"
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

                <x-form.textarea class="pastejs edit" label="Стъпки за пресъздаване"
                  placeholder="Стъпки за пресъздаване" x-model="steps_to_reproduce" data-type="steps">
                </x-form.textarea>

                <x-form.group class="my-2">
                  <div>
                    <label>Снимки пресъздаване
                      <span class="text-black-50">(двоен клик за изтриване)</span>
                    </label>
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
            <div class="col-md-6">
              <div class="text-center">
                <label class="btn"
                  x-bind:class="{ 'btn-outline-primary': status != {{ Task::status_to_do }}, 'btn-primary': status == {{ Task::status_to_do }}}"><input
                    type="radio" x-model="status" value="{{ Task::status_to_do }}"
                    class="d-none">{{ Task::status_to_do_text }}</label>
                <label class="btn"
                  x-bind:class="{ 'btn-outline-primary': status != {{ Task::status_in_progress }}, 'btn-primary': status == {{ Task::status_in_progress }}}"><input
                    type="radio" x-model="status" value="{{ Task::status_in_progress }}"
                    class="d-none">{{ Task::status_in_progress_text }}</label>
                <label class="btn"
                  x-bind:class="{ 'btn-outline-primary': status != {{ Task::status_awaiting_upload }}, 'btn-primary': status == {{ Task::status_awaiting_upload }}}"><input
                    type="radio" x-model="status" value="{{ Task::status_awaiting_upload }}"
                    class="d-none">{{ Task::status_awaiting_upload_text }}</label>
                <label class="btn"
                  x-bind:class="{ 'btn-outline-primary': status != {{ Task::status_ready_for_qa }}, 'btn-primary': status == {{ Task::status_ready_for_qa }}}"><input
                    type="radio" x-model="status" value="{{ Task::status_ready_for_qa }}"
                    class="d-none">{{ Task::status_ready_for_qa_text }}</label>
                <label class="btn"
                  x-bind:class="{ 'btn-outline-primary': status != {{ Task::status_resolved }}, 'btn-primary': status == {{ Task::status_resolved }}}"><input
                    type="radio" x-model="status" value="{{ Task::status_resolved }}"
                    class="d-none">{{ Task::status_resolved_text }}</label>
                <label class="btn"
                  x-bind:class="{ 'btn-outline-primary': status != {{ Task::status_not_resolved }}, 'btn-primary': status == {{ Task::status_not_resolved }}}"><input
                    type="radio" x-model="status" value="{{ Task::status_not_resolved }}"
                    class="d-none">{{ Task::status_not_resolved_text }}</label>
                <label class="btn"
                  x-bind:class="{ 'btn-outline-primary': status != {{ Task::status_awaiting_feedback }}, 'btn-primary': status == {{ Task::status_awaiting_feedback }}}"><input
                    type="radio" x-model="status" value="{{ Task::status_awaiting_feedback }}"
                    class="d-none">{{ Task::status_awaiting_feedback_text }}</label>
              </div>

              <div>
                <h3>История</h3>
                <ul class="list-group">
                  <template x-for="(event, index) in history" :key="index">
                    <li class="list-group-item d-flex justify-content-between align-items-start">
                      <div class="ms-2 w-100">
                        <div class="d-flex justify-content-between">
                          <div>
                            <span class="fw-bold" x-text="event.status_text"></span>
                            <template x-if="event.user_id">
                              <span x-text="event.user.name"></span>
                            </template>
                          </div>
                          <div x-text="dateFormat(event.created_at)"></div>
                        </div>
                      </div>
                    </li>
                  </template>
                </ul>
              </div>
              <div class="mt-4">
                <h3>Коментари</h3>
                <ul class="list-group list-group-numbered">
                  <template x-for="(comment, index) in comments" :key="index">
                    <li class="list-group-item d-flex justify-content-between align-items-start">
                      <div class="ms-2 w-100">
                        <div x-text='comment.comment'></div>
                        <div class="d-flex justify-content-between">
                          <div class="fw-bold" x-text="comment.username"></div>
                          <div x-text="dateFormat(comment.created_at)"></div>
                        </div>
                      </div>
                    </li>
                  </template>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" x-on:click="save()" data-bs-dismiss="modal">Запази</button>
      </div>
    </div>
  </div>
</div>
<!-- /edit modal -->
