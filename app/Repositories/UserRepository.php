<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserRepository
{
    /**
     * Връща всички потребители
     * @param boolean $team_only дали да покаже само тези, които са назначени в екипа на фирмата
     */
    public static function all($team_only = true)
    {
        return User::when($team_only, function ($q) {
            $q->whereIn('acc_type', [
                User::role_admin,
                User::role_team,
            ]);
        })
            ->get();
    }

    public static function find($id)
    {
        return User::where('id', $id)->firstOrFail();
    }

    public static function paginate($name = null)
    {
        return User::when($name, function ($q) use ($name) {
            $pseudonymization = pseudonymization($name);
            $q->where('name', 'like', "%$name%")
                ->orWhere(DB::raw('BINARY `email`'), 'like', "%$pseudonymization%");
        })->latest('id')->paginate();
    }

    public static function update($user, $input)
    {
        if (isset($input['password'])) {
            $input['password'] = bcrypt($input['password']);
        }
        $user->update($input);

        if (isset($input['acc_type'])) {
            $user->acc_type = $input['acc_type'] == 0 ? null : $input['acc_type'];
            $user->save();
        }
        return $user;
    }

    public static function delete($user, $reason = '')
    {
        // обновяване на профила
        $email = explode('@', $user->email);
        $user->email = time() . 'del-' . rand(1, 999) . '@' . $email[1];
        $user->name = 'Deleted user';
        $user->delete_reason = $reason;
        $user->save();
        // изтриване на профила
        $user->delete();
    }
}
