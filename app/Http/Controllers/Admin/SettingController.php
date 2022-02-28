<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Repositories\SettingRepository;
use Illuminate\Http\Request;
use Prologue\Alerts\Facades\Alert;

class SettingController extends Controller
{
    public function index()
    {
        $params = [];
        $page = [];

        $actions = [
            ['link' => route('admin.settings.create'), 'anchor' => 'Добави Настрийка', 'type' => 'btn-primary'],
        ];

        $breadcrumbs = [
            'Настройки' => false,
        ];

        $page['actions'] = $actions;
        $page['breadcrumbs'] = $breadcrumbs;
        $page['page_title'] = "Настройки";
        $params['page'] = $page;
        $params['settings'] = SettingRepository::paginate();
        return view('admin.settings.index', $params);
    }
    
    public function create()
    {
        $params = [];
        $page = [];

        $actions = [
            ['link' => route('admin.settings.index'), 'anchor' => 'Назад', 'type' => 'btn-outline-primary'],
        ];

        $breadcrumbs = [
            'Настройки' => route('admin.settings.index'),
            'Добави Настройка' => false,
        ];

        $page['actions'] = $actions;
        $page['breadcrumbs'] = $breadcrumbs;
        $page['page_title'] = "Добави Настройка";
        $params['page'] = $page;
        // form
        $params['action'] = route('admin.settings.store');
        $params['method'] = 'post';

        return view('admin.settings.form', $params);
    }
    
    public function store(Request $request)
    {
        $this->validate($request, [
            'key' => ['required', 'unique:settings,key'],
            'value' => 'required',
        ]);
        $input = $request->only(['key', 'value',]);

        try {
            $setting = SettingRepository::create($input);
            activity_log('Setting', 'store', $setting->id, auth()->user()->id, json_encode($input, JSON_UNESCAPED_UNICODE));

            Alert::add('success', 'Настройката е добавена.')->flash();
            return redirect()->route('admin.settings.edit', $setting->id);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                dd($e);
            }
            abort(500);
        }
    }
    
    public function show(Setting $setting)
    {
        //
    }
    
    public function edit(Setting $setting)
    {
        $params = [];
        $page = [];

        $actions = [
            ['link' => route('admin.settings.index'), 'anchor' => 'Назад', 'type' => 'btn-outline-primary'],
        ];

        $page['delete'] = route('admin.settings.destroy', [$setting->id]);

        $breadcrumbs = [
            'Настройки' => route('admin.settings.index'),
            'Редактирай Настройка' => false,
        ];

        $page['actions'] = $actions;
        $page['breadcrumbs'] = $breadcrumbs;
        $page['page_title'] = "Редактирай Настройка";
        $params['page'] = $page;
        $params['setting'] = $setting;
        // form
        $params['action'] = route('admin.settings.update', $setting->id);
        $params['method'] = 'PUT';

        return view('admin.settings.form', $params);
    }

    public function update(Request $request, Setting $setting)
    {
        $this->validate($request, [
            'key' => ['required', 'unique:settings,key,'. $setting->id],
            'value' => 'required',
        ]);
        $input = $request->only(['key', 'value',]);

        try {
            $setting = SettingRepository::update($setting, $input);

            activity_log('Setting', 'update', $setting->id, auth()->user()->id, json_encode($input, JSON_UNESCAPED_UNICODE));

            Alert::add('success', 'Настройка е обновена.')->flash();
            return redirect()->route('admin.settings.edit', $setting->id);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                dd($e);
            }
            abort(500);
        }
    }

    public function destroy(Setting $setting)
    {
        SettingRepository::delete($setting);

        activity_log('Setting', 'destroy', $setting->id, auth()->user()->id, "destroy $setting->symbol");
        Alert::add('success', 'Настройка е изтрита.')->flash();
        return redirect()->route('admin.settings.index');
    }

}
