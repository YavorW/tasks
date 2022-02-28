<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Repositories\ProjectRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Prologue\Alerts\Facades\Alert;

class ProjectController extends Controller
{
    public function index()
    {
        $params = [];
        $page = [];

        $actions = [
            ['link' => route('projects.create'), 'anchor' => 'Добави Проект', 'type' => 'btn-primary'],
        ];

        $breadcrumbs = [
            'Проекти' => false,
        ];

        $page['actions'] = $actions;
        $page['breadcrumbs'] = $breadcrumbs;
        $page['page_title'] = "Проекти";
        $params['page'] = $page;
        $params['projects'] = ProjectRepository::paginate(request('page'), request('name'));

        return view('client.projects.index', $params);
    }

    public function create()
    {
        $params = [];
        $page = [];

        $actions = [
            ['link' => route('projects.index'), 'anchor' => 'Назад', 'type' => 'btn-outline-primary'],
        ];

        $breadcrumbs = [
            'Проекти' => route('projects.index'),
            'Добави Проект' => false,
        ];

        $page['actions'] = $actions;
        $page['breadcrumbs'] = $breadcrumbs;
        $page['page_title'] = "Добави Проект";
        $params['page'] = $page;

        $params['users'] = UserRepository::all(true);

        // form
        $params['action'] = route('projects.store');
        $params['method'] = 'post';

        return view('client.projects.form', $params);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);
        $input = $request->except(['_method', '_token']);

        try {
            $project = ProjectRepository::create($input);
            activity_log('Projects', 'store', $project->id, auth()->user()->id, json_encode($input, JSON_UNESCAPED_UNICODE));

            Alert::add('success', 'Project added.')->flash();
            return redirect()->route('projects.edit', $project->id);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                dd($e);
            }
            abort(500);
        }
    }

    public function edit(Project $project)
    {
        $params = [];
        $page = [];

        $actions = [
            ['link' => route('projects.show', $project->id), 'anchor' => 'Задачи', 'type' => 'btn-outline-primary'],
            ['link' => route('projects.index'), 'anchor' => 'Назад', 'type' => 'btn-outline-secondary'],
        ];

        if (Gate::allows('admin')) {
            $page['delete'] = route('projects.destroy', [$project->id]);
        }

        $breadcrumbs = [
            'Проекти' => route('projects.index'),
            "Редактирай $project->name" => false,
        ];

        $page['actions'] = $actions;
        $page['breadcrumbs'] = $breadcrumbs;
        $page['page_title'] = "Редактирай $project->name";
        $params['page'] = $page;
        $params['project'] = $project;
        $params['users'] = UserRepository::all(true);
        $teams = $project->users;
        foreach ($teams as $user) {
            $params['teams'][$user->pivot->role][] = $user->id;
        }
        // form
        $params['action'] = route('projects.update', $project->id);
        $params['method'] = 'PUT';

        return view('client.projects.form', $params);
    }

    public function update(Request $request, Project $project)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);
        $input = $request->except(['_method', '_token']);

        try {
            $project = ProjectRepository::update($project, $input);

            activity_log('Projects', 'update', $project->id, auth()->user()->id, json_encode($input, JSON_UNESCAPED_UNICODE));

            Alert::add('success', 'Project updated.')->flash();
            return redirect()->route('projects.edit', $project->id);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                dd($e);
            }
            abort(500);
        }
    }

    public function destroy(Project $project)
    {
        if (Gate::allows('admin')) {
            ProjectRepository::delete($project);
            
            activity_log('Projects', 'destroy', $project->id, auth()->user()->id, "destroy $project->symbol");
            Alert::add('success', 'Project deleted.')->flash();
        }
        return redirect()->route('projects.index');
    }

}
