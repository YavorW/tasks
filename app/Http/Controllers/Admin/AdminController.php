<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Log;
use App\Models\Message;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.index');
    }

    public function logs()
    {
        $request = request();
        $params = [];
        $actions = [];
        if ($request->all()) {
            $actions = [
                ['link' => route('admin.logs.index'), 'anchor' => 'Back', 'type' => 'btn-primary'],
            ];
        }

        $breadcrumbs = [
            'Activity log' => false,
        ];

        $page['actions'] = $actions;
        $page['breadcrumbs'] = $breadcrumbs;
        $page['page_title'] = "Log Explorer";
        $params['page'] = $page;

        $logs = Log::when(($request->input('type') != ''), function ($q) use ($request) {
            return $q->where('model_type', $request->type);
        })->when(($request->input('id') != ''), function ($q) use ($request) {
            return $q->where('model_id', $request->id);
        })->when(($request->input('user') != ''), function ($q) use ($request) {
            return $q->where('user_id', $request->user);
        })
            ->orderBy('created_at', 'desc')->paginate(paginate);

        $params['logs'] = $logs;
        return view('admin.log-index', $params);
    }

    /**
     * Записва иконите в папката images/icons
     *
     * @param Request $request
     * @return json
     */
    public function storeIcon(Request $request)
    {
        $this->validate($request, [
            'icon' => 'required|image',
        ]);
        $return = [];
        $return['icon'] = storeIcon($request->icon);
        return json_encode($return);
    }

    public function chat()
    {
        $request = request();
        $params = [];
        $actions = [];
        if ($request->all()) {
            $actions = [
                ['link' => route('admin.chat.index'), 'anchor' => 'Back', 'type' => 'btn-primary'],
            ];
        }

        $breadcrumbs = [
            'Admin' => route('admin.index'),
            'Chat Log' => false,
        ];

        $page['actions'] = $actions;
        $page['breadcrumbs'] = $breadcrumbs;
        $page['page_title'] = "Chat Log";
        $params['page'] = $page;

        $chat = Message::when($request->user, function ($q) use ($request) {
            return $q->where('user_id', $request->user);
        })
            ->when(($request->date), function ($q) use ($request) {
                return $q->where('created_at', '>=', $request->date . ' 00:00:00')
                    ->where('created_at', '<=', $request->date . ' 23:59:59');
            })
            ->orderBy('created_at', 'desc')
            ->with(['user', 'likes'])
            ->paginate(30);

        $params['chat'] = $chat;
        return view('admin.chat-index', $params);
    }
}
