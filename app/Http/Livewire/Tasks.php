<?php

namespace App\Http\Livewire;

use App\Models\Comment;
use App\Models\Project;
use App\Models\Task;
use App\Repositories\CommentRepository;
use App\Repositories\TaskRepository;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class Tasks extends Component
{
    public $project, $users;
    public $filter_type, $filter_priority, $filter_status, $filter_team, $filter_user;

    protected $queryString = [
        'filter_type' => ['except' => ''],
        'filter_priority' => ['except' => ''],
        'filter_status' => ['except' => ''],
        'filter_team' => ['except' => ''],
        'filter_user' => ['except' => ''],
    ];

    protected $listeners = ['rerender' => '$refresh'];

    public function mount(Project $project)
    {
        $this->project = $project;
        $this->users = $project->users(true)->get();
    }

    /** Създаване на задача */
    public function store(
        $subject = null,
        $description = null,
        $steps_to_reproduce = null,
        $type = null,
        $priority = null,
        $team = null,
        $user = null,
        $files = ['task' => [], 'description' => [], 'steps' => []]) {
        $input = [
            'project_id' => $this->project->id,
            'user_id' => $user,
            'team' => $team,
            'subject' => $subject,
            'description' => $description,
            'steps_to_reproduce' => $steps_to_reproduce,
            'type' => $type,
            'priority' => $priority,
            'files' => json_encode($files),
        ];
        $task = TaskRepository::create($input);
        $this->emit('rerender');
        $this->emit('alert', ['type' => 'success', 'message' => "Задачата е създадена."]);

    }

    /** записване на снимка при поставяне */
    public function pasteImage($dataUrl, $type = 'description', $modal = null)
    {
        if (!$modal) {
            return;
        }
        $contents = base64_decode(substr($dataUrl, strlen('data:image/png;base64,')));
        $date = date("Y-m-d-H-i-s");
        do {
            $file = $this->project->id . '/' . $date . '-' . rand(1, 10000) . '.png';
        } while (Storage::disk('projects')->exists($file));
        Storage::disk('projects')->put($file, $contents);
        $this->dispatchBrowserEvent('paste-image', [
            'modal' => $modal,
            'type' => $type,
            'uri' => $file,
        ]);
    }

    public function taskUpdate(Task $task, $input = null)
    {
        if (is_array($input)) {
            $task = TaskRepository::update($task, $input);
            $task = TaskRepository::find($task->id);
            $this->emit('alert', ['type' => 'success', 'message' => "Задачата е обновена."]);
            $this->dispatchBrowserEvent('task-reload', ['task' => $task]);

        }
    }

    public function taskUpdateProperty(Task $task, $property = null, $value = null)
    {
        if ($property) {
            $value = $value == '' ? null : $value;
            TaskRepository::update($task, [$property => $value]);
            $this->emit('alert', ['type' => 'success', 'message' => "Задачата е обновена."]);
        }
    }

    public function deleteTask(Task $task)
    {
        TaskRepository::delete($task);
        $this->emit('alert', ['type' => 'success', 'message' => "Задачата е изтрита."]);
    }

    /** Създаване на коментар */
    public function addComment($task_id, $comment)
    {
        $comment = CommentRepository::create([
            'task_id' => $task_id,
            'user_id' => auth()->id(),
            'comment' => $comment,
        ]);

        $this->dispatchBrowserEvent('load-comment', ['task_id' => $task_id, 'comment' => $comment]);
    }

    public function editComment(Comment $comment, $value)
    {
        CommentRepository::update($comment, ['comment' => $value]);
    }

    public function deleteComment(Comment $comment)
    {
        CommentRepository::delete($comment);
    }

    public function render()
    {
        $params = [];
        $page = [];

        $actions = [
            ['link' => route('projects.edit', $this->project->id), 'anchor' => 'Към Прокта', 'type' => 'btn-outline-primary'],
            ['link' => route('projects.index'), 'anchor' => 'Назад', 'type' => 'btn-outline-secondary'],
        ];

        $breadcrumbs = [
            'Проекти' => route('projects.index'),
            "Задачи {$this->project->name}" => false,
        ];
        $page['livewire'] = true;

        $page['actions'] = $actions;
        $page['breadcrumbs'] = $breadcrumbs;
        $page['page_title'] = "Задачи {$this->project->name}";

        $filters = [];
        if ($this->filter_type) {
            $filters['type'] = $this->filter_type;
        }
        if ($this->filter_priority) {
            $filters['priority'] = $this->filter_priority;
        }
        if ($this->filter_status) {
            $filters['status'] = $this->filter_status;
        }
        if ($this->filter_team) {
            $filters['team'] = $this->filter_team;
        }
        if ($this->filter_user) {
            $filters['user_id'] = $this->filter_user;
        }

        $params['tasks'] = TaskRepository::all($this->project, null, null, true, $filters) ?? [];

        return view('client.projects.tasks', $params)->layout('layouts.app', $page);
    }
}
