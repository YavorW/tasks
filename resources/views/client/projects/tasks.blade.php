<?php
use App\Models\Project;
use App\Models\Task;
?>
<div class="card mx-3">
  <style>
    .task-table td:nth-child(2),
    .task-table td:nth-child(3),
    .task-table td:nth-child(4)
     {
      min-width: 300px
    }
    .task-table td:nth-child(5),
    .task-table td:nth-child(6) {
      min-width: 120px
    }

    .task-table td:nth-child(7) {
      min-width: 160px
    }

    .task-table td:nth-child(8) {
      min-width: 130px
    }

    .task-table td:nth-child(9),
    .task-table td:nth-child(10) {
      min-width: 200px
    }

    .max-235 {
      max-width: 235px;
      max-height: 235px;
    }
    td .max-235 {
      display: block;
    }
    .zoom-overlay-open .table-responsive {
      overflow : initial;
    }
    img.zoom-img {
      max-height: none;
      max-width: none;
    }
    .zoom-img, .zoom-img-wrap {
      -webkit-transition: easy-out 300ms; 
      -o-transition: easy-out 300ms;
      transition: easy-out 300ms;
    }
  </style>
  <div class="card-body">
    <div class="table-responsive">
      <table class="table task-table" wire:poll.60s>
        <tr>
          <th>Id</th>
          <th>Задача</th>
          <th>Описание</th>
          <th>Стъпки за пресъздаване</th>
          <th>Статус</th>
          <th>Тип</th>
          <th>Приоритет</th>
          <th>За екип</th>
          <th>Изпълнител</th>
          <th>Коментари</th>
        </tr>
        <tr class="border-bottom border-primary">
          @include('client.projects.filters')
        </tr>
        @foreach ($tasks as $task)
          <tr x-data="task(@js($task))" x-on:task-reload.window="if($event.detail.task.id == id) reload($event.detail.task)" >
            <td>
              <a href="{{ route('projects.show', [$project->id, 'task' => $task->id]) }}"
                class="btn border-2"
                x-bind:class="{
                  'btn-primary': status == {{ Task::status_to_do }},
                  'btn-info': status == {{ Task::status_in_progress }},
                  'btn-secondary': status == {{ Task::status_awaiting_upload }},
                  'btn-dark': status == {{ Task::status_ready_for_qa }},
                  'btn-success': status == {{ Task::status_resolved }},
                  'btn-danger': status == {{ Task::status_not_resolved }},
                  'btn-warning': status == {{ Task::status_awaiting_feedback }},
                }"

                x-on:click='open($dispatch); $event.preventDefault();'
                x-on:open-task.window="if($event.detail.id == id) open($dispatch)"
                >{{ $task->id }}</a>
            </td>
            <td>
              {!! convertUrlsToLinks(nl2br($task->subject)) !!}
              @if (isset($task->files()['task']) && !empty($task->files()['task']))
                @foreach ($task->files()['task'] as $img)
                  <img src="{{ $task->asset($img) }}" class="max-235 img-thumbnail m-1" data-action="zoom">
                @endforeach
              @endif
            </td>
            <td>
              {!! convertUrlsToLinks(nl2br($task->description)) !!}
              @if (isset($task->files()['description']) && !empty($task->files()['description']))
                @foreach ($task->files()['description'] as $img)
                  <img src="{{ $task->asset($img) }}" class="max-235 img-thumbnail m-1" data-action="zoom">
                @endforeach
              @endif
            </td>
            <td>
              {!! convertUrlsToLinks(nl2br($task->steps_to_reproduce)) !!}
              @if (isset($task->files()['steps']) && !empty($task->files()['steps']))
                @foreach ($task->files()['steps'] as $img)
                  <img src="{{ $task->asset($img) }}" class="max-235 img-thumbnail m-1" data-action="zoom">
                @endforeach
              @endif
            </td>
            <td>
              <x-form.select x-model="status" x-on:change="update($event, 'status')"
                class="border border-2"
                x-bind:class="{
                  'border-primary': status == {{ Task::status_to_do }},
                  'border-info': status == {{ Task::status_in_progress }},
                  'border-secondary': status == {{ Task::status_awaiting_upload }},
                  'border-dark': status == {{ Task::status_ready_for_qa }},
                  'border-success': status == {{ Task::status_resolved }},
                  'border-danger': status == {{ Task::status_not_resolved }},
                  'border-warning': status == {{ Task::status_awaiting_feedback }},
                }"
              >
                <option value="{{ Task::status_to_do }}">To Do</option>
                <option value="{{ Task::status_in_progress }}">In progress</option>
                <option value="{{ Task::status_awaiting_upload }}">Awaiting Upload</option>
                <option value="{{ Task::status_ready_for_qa }}">Ready for QA</option>
                <option value="{{ Task::status_resolved }}">Resolved</option>
                <option value="{{ Task::status_not_resolved }}">Not Resolved</option>
                <option value="{{ Task::status_awaiting_feedback }}">Awaiting Feedback</option>
              </x-form.select>
            </td>
            <td>
              <x-form.select x-model="type" x-on:change="update($event, 'type')">
                <option value="{{ Task::type_bug }}">Bug</option>
                <option value="{{ Task::type_new_feature }}">New Feature</option>
                <option value="{{ Task::type_change }}">Change</option>
              </x-form.select>
            </td>
            <td>
              <x-form.select x-model="priority" x-on:change="update($event, 'priority')">
                <option value="{{ Task::priority_lowest }}">Lowest</option>
                <option value="{{ Task::priority_low }}">Low</option>
                <option value="{{ Task::priority_medium }}">Medium</option>
                <option value="{{ Task::priority_high }}">High</option>
                <option value="{{ Task::priority_highest }}">Highest</option>
              </x-form.select>
            </td>
            <td>
              <x-form.select x-model="team" x-on:change="update($event, 'team')">
                <option value="">Избери</option>
                <option value="{{ Project::team_manager }}">Менижъри</option>
                <option value="{{ Project::team_qa }}">QA</option>
                <option value="{{ Project::team_backend }}">Back-End</option>
                <option value="{{ Project::team_frontend }}">Front-End</option>
                <option value="{{ Project::team_design }}">Дизайн</option>
                <option value="{{ Project::team_support }}">Поддръжка</option>
              </x-form.select>
            </td>
            <td>
              <x-form.select x-model="user_id" x-on:change="update($event, 'user_id')">
                <option value="">Избери</option>
                @if ($users)
                  @foreach ($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                  @endforeach
                @endif
              </x-form.select>
            </td>
            <td>
              <button type="button" class="btn btn-outline-primary position-relative"
                x-on:click='$dispatch("load-comments", getData());'>
                Коментари
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                  x-show="comments.length" x-text="comments.length"></span>
              </button>
              <template x-if="comments.length > 0">
                <div>
                  <span x-text="comments[0].username"></span>:
                  <span x-text="comments[0].comment"></span>
                </div>
              </template>
            </td>
          </tr>
        @endforeach
      </table>
    </div>

    <div wire:ignore>
      <div class="text-center mt-2">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#create-modal">
          <i class="fas fa-plus-circle"></i> Добави задача
        </button>
      </div>
      <script>
        function task(task) {
          let files = task.files ? JSON.parse(task.files) : [];
          return {
            id: task.id,
            subject: task.subject,
            description: task.description,
            steps_to_reproduce: task.steps_to_reproduce,
            files: files,
            type: task.type,
            priority: task.priority,
            status: task.status,
            team: task.team,
            user_id: task.user_id,
            history: task.history,
            comments: task.comments,
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
                files: this.files,
                history: this.history,
                comments: this.comments
              }
            },
            update($event, property) {
              @this.taskUpdateProperty(this.id, property, $event.target.value)
            },
            open($dispatch) {
              $dispatch("load-task", this.getData());
            },
            reload(task) {
              console.log(task);
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
            }
          }
        }

        function date2str(x, y) {
          var z = {
            M: x.getMonth() + 1,
            d: x.getDate(),
            h: x.getHours(),
            m: x.getMinutes(),
            s: x.getSeconds()
          };
          y = y.replace(/(M+|d+|h+|m+|s+)/g, function(v) {
            return ((v.length > 1 ? "0" : "") + z[v.slice(-1)]).slice(-2)
          });

          return y.replace(/(y+)/g, function(v) {
            return x.getFullYear().toString().slice(-v.length)
          });
        }

        function dateFormat(datetime) {
          return date2str(new Date(datetime), 'dd-MM-yyyy hh:mm:ss')
        }
      </script>

      @include('client.projects.modals.create-modal')
      @include('client.projects.modals.edit-modal')
      @include('client.projects.modals.comments-modal')

    </div>
  </div>
</div>
@push('actions')
<button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#create-modal">
  <i class="fas fa-plus-circle"></i> Добави задача
</button>
@endpush
@push('styles')
  <link rel="stylesheet" href="{{ asset('plugins/zoom.js/css/zoom.css') }}">
@endpush
@push('scripts')
  <script type="text/javascript" src="{{ asset('plugins/zoom.js/dist/zoom.min.js') }}"></script>
  {{-- https://github.com/layerssss/paste.js --}}
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/paste.js/0.0.21/paste.min.js"></script>
  <script>
    @if (empty($tasks))
      // да отваря модала за създаване със зареждането на страницата, когато няма вкарани таскове
      $(function () {
      const create_modal = new bootstrap.Modal(document.getElementById('create-modal'));
      create_modal.show();
      })
    @endif

    // да отваря модала за редактиране със зареждането на страницата
    @if (request('task'))
      $(function () {
      const event = new CustomEvent('open-task', { detail: { id: {{ request('task') }} } });
      window.dispatchEvent(event);
      })
    @endif

  </script>
@endpush
