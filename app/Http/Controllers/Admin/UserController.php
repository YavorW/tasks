<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Repositories\WalletRepository;
use Illuminate\Http\Request;
use Prologue\Alerts\Facades\Alert;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $params = [];
        $page = [];

        $actions = [
        ];

        $breadcrumbs = [
            'Потребители' => false,
        ];

        $page['actions'] = $actions;
        $page['breadcrumbs'] = $breadcrumbs;
        $page['page_title'] = "Потребители";
        $params['page'] = $page;
        $params['users'] = UserRepository::paginate(request('name'));
        return view('admin.users.index', $params);
    }

    public function edit(User $user)
    {
        $params = [];
        $page = [];

        $actions = [
            ['link' => route('admin.users.index'), 'anchor' => 'Назад', 'type' => 'btn-outline-primary'],
        ];

        $page['delete'] = route('admin.users.destroy', [$user->id]);

        $breadcrumbs = [
            'Потребители' => route('admin.users.index'),
            'Редактирай Потребител' => false,
        ];

        $page['actions'] = $actions;
        $page['breadcrumbs'] = $breadcrumbs;
        $page['page_title'] = "Редактирай Потребител";
        $params['page'] = $page;
        $params['user'] = $user;
        // form
        $params['action'] = route('admin.users.update', $user->id);
        $params['method'] = 'PUT';
        
        return view('admin.users.form', $params);
    }
    
    public function update(Request $request, User $user)
    {
        $this->validate($request, [
            'acc_type' => 'required',
        ]);
        $input = $request->only(['acc_type',]);

        try {
            $user = UserRepository::update($user, $input);

            activity_log('User', 'update', $user->id, auth()->user()->id, json_encode($input, JSON_UNESCAPED_UNICODE));

            Alert::add('success', 'Потребителят е обновен.')->flash();
            return redirect()->route('admin.users.edit', $user->id);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                dd($e);
            }
            abort(500);
        }
    }
    
    public function destroy(User $user)
    {
        UserRepository::delete($user,'from admin');

        activity_log('User', 'destroy', $user->id, auth()->user()->id, "destroy $user->symbol");
        Alert::add('success', 'Потребителят е изтрит')->flash();
        return redirect()->route('admin.users.index');
    }

}
