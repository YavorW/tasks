<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Repositories\ProjectRepository;
use App\Repositories\TaskRepository;

class HomeController extends Controller
{
    public function index()
    {
        $params = [];
        $page = [];

        $actions = [
        ];

        $breadcrumbs = [
            'Табло' => false,
        ];

        $page['actions'] = $actions;
        $page['breadcrumbs'] = $breadcrumbs;
        $page['page_title'] = "Табло";
        $params['page'] = $page;

        /** @var User */
        $user = auth()->user();
        // подредени спрямо последно обновяване от задачите
        $projects = ProjectRepository::all($user);
        $params['user'] = $user;
        $params['projects'] = $projects;
        $tasks = [];
        // събиране на задачи към всички проекти, в които
        foreach ($projects as $project) {
            $roles = $project->users->where('id', $user->id);
            // $user_role е модел User
            foreach ($roles as $user_role) {
                if ($user_role->pivot->role == Project::team_qa) {
                    // ролята е team_qa - всички задачи, които са status_ready_for_qa
                    $tasks[$project->id][] = TaskRepository::all($project, null, Task::status_ready_for_qa, false);
                } else if ($user_role->pivot->role == Project::team_manager) {
                    // ролята е team_manager - всички задачи, които са status_awaiting_feedback
                    $tasks[$project->id][] = TaskRepository::all($project, null, Task::status_awaiting_feedback);
                } else if (in_array($user_role->pivot->role, [
                    Project::team_backend,
                    Project::team_frontend,
                    Project::team_design,
                ])) {
                    // ролята е team_backend, team_frontend, team_design - всички задачи, които са:
                    // status_to_do, status_in_progress, status_awaiting_upload, status_not_resolved
                    $tasks[$project->id][] = TaskRepository::all($project, $user, [
                        Task::status_to_do,
                        Task::status_in_progress,
                        Task::status_awaiting_upload,
                        Task::status_not_resolved,
                    ]);
                }
            }
        }
        $params['tasks'] = $tasks;
        return view('client.home', $params);
    }
}
