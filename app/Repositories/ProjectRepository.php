<?php

namespace App\Repositories;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ProjectRepository
{
    public static function find($id)
    {
        return Project::where('id', $id)->first();
    }

    /** Всички проекти с потребител, подредени по последна промяна на таск */
    public static function all(?User $user = null)
    {
        return Project::when($user, function ($q) use ($user) {
            $q->whereHas('users', function($q) use($user) {
                $q->where('users.id',$user->id);
            })->with(['users']);
        })
            ->addSelect([
                'tasks_updated_at' => Task::select('updated_at')
                    ->whereColumn('project_id', 'projects.id')
                    ->orderByDesc('updated_at')
                    ->limit(1),
            ])
            ->orderBy('tasks_updated_at', 'desc')
            ->get();
    }

    public static function paginate($page, $name = null)
    {
        return Project::addSelect([
            'tasks_updated_at' => Task::select('updated_at')
                ->whereColumn('project_id', 'projects.id')
                ->orderByDesc('updated_at')
                ->limit(1),
        ])
            ->when($name, function ($q) use ($name) {
                $q->where('name', 'like', "%$name%");
            })
            ->orderBy('tasks_updated_at', 'desc')
            ->paginate();
    }

    public static function create(array $input)
    {
        $teams = isset($input['teams']) ? $input['teams'] : [];
        unset($input['teams']);
        // Създаване на проекта
        $project = Project::create($input);
        // закачане на потребителтие с ролите
        self::syncProjectUsers($project, $teams);
        return $project;
    }

    /** Разпределя потребителите по роли */
    private static function syncProjectUsers(Project $project, array $teams)
    {
        DB::table('project_user')->where('project_id', $project->id)->delete();
        $project_user = [];
        foreach ($teams as $team => $users) {
            foreach ($users as $user_id) {
                $project_user[] = [
                    'project_id' => $project->id,
                    'user_id' => $user_id,
                    'role' => $team,
                ];
            }
        }
        DB::table('project_user')->insert($project_user);
    }

    public static function update($project, array $input)
    {
        $teams = isset($input['teams']) ? $input['teams'] : [];
        unset($input['teams']);
        // обновяване на проекта
        $project->update($input);
        // закачане на потребителтие с ролите
        self::syncProjectUsers($project, $teams);
        return $project;
    }

    public static function delete($project)
    {
        $project->delete();
    }

}
