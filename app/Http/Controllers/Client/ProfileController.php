<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Prologue\Alerts\Facades\Alert;

class ProfileController extends Controller
{


    /**
     * Страница за настройки на профила
     * @link get /profile
     *
     * @param Request $request
     * @return void
     */
    function profile(Request $request)
    {
        $params = [];
        $params['user'] = Auth::user();
        return view('client.profile.settings', $params);
    }
    /**
     * Обновяване на профила
     *  @link post /profile
     *
     * @param Request $request
     * @return void
     */
    public function update(Request $request)
    {
        if ($request->input('password')) {
            $this->validate($request, [
                'name' => 'required|string|max:255',
                'password' => 'string|min:6|confirmed',
            ]);
        } else {
            $this->validate($request, [
                'name' => 'required|string|max:255',
            ]);
        }
        /**@var User */
        $user = Auth::user();

        $user->name = $request->name;

        if($request->avatar) {
            $user->avatar = storeUserFile($request->avatar, "$user->id/avatar");
        }

        if ($request->input('password'))
            $user->password = bcrypt($request->input('password'));
        $user->save();
        Alert::add('success', 'Профила е обновен.')->flash();

        return redirect()->back()->with('profile-updated');
    }

    public function delete(Request $request) {
        /** @var User */
        $user = auth()->user();
        // изтриване на участията
        // giveaway
        $user->giveaways()->delete();
        // tournaments
        $user->tournaments()->delete();
        // chat
        // първо се изтриват лайковете от потребителя
        $user->chat_likes()->delete();
        // после лайковете на съобщенията от потребителя
        foreach($user->chat_messages as $message) {
            $message->likes()->delete();
        }
        // накрая самите съобщения
        $user->chat_messages()->delete();

        // обновяване на профила
        $email = explode('@', $user->email);
        $user->email = time() . 'del-'. rand(1,999) . '@' . $email[1];
        $user->name = 'Deleted user';
        $user->delete_reason = $request->delete_reason;
        $user->save();
        // изтриване на профила
        $user->delete();
        return redirect('/')->with('success','Account deleted');

    }

}
