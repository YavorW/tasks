<?php

namespace App\Repositories;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;

class TaskRepository
{
    public static function find($id)
    {
        return Task::where('id', $id)
            ->with(['history', 'history.user:id,name', 'comments'])
            ->first();
    }

    /**
     * @param Project|null $project
     * @param User|null $user
     * @param integer|null $status
     * @param boolean $with дали да добави релациите
     */
    public static function all(?Project $project = null, ?User $user = null, $status = null, $with = true, $filters = [])
    {
        $builder = Task::latest()
            ->when($with, function ($q) {
                $q->with(['history', 'history.user:id,name', 'comments']);
            })
            ->when($project, function ($q) use ($project) {
                $q->where('project_id', $project->id);
            })
            ->when($user, function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->when($status, function ($q) use ($status) {
                if (is_array($status)) {
                    $q->whereIn('status', $status);
                } else {
                    $q->where('status', $status);
                }
            });
        foreach ($filters as $filter => $value) {
            $builder = $builder->where($filter, $value);
        }
        // dd(sqlDump($builder));
        // dd($builder->get());
        return $builder->get();
    }

    public static function paginate()
    {
        return Task::latest()->paginate();
    }

    public static function create(array $input)
    {
        return Task::create($input);
    }

    public static function update(Task $task, array $input)
    {
        $status_history = isset($input['status']) && $task->status != $input['status'];
        $task->update($input);
        // записване на промяна на история на статуса
        if ($status_history) {
            $task->history()->create([
                'status' => $input['status'],
                'task_id' => $task->id,
                'user_id' => auth()->id(),
            ]);
        }
        return $task;
    }

    public static function delete(Task $task)
    {
        $task->delete();
    }
}
