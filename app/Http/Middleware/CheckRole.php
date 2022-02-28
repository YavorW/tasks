<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, ...$roles)
    {
        // is user logged
        if (!Auth::check())
            return redirect('login');

        /**
         * @var User
         */
        $user = Auth::user();

        if ($user->can('admin'))
            return $next($request);
        
        // if has any role
        foreach ($roles as $role) {
            if ($user->can($role))
                return $next($request);
        }
        abort(403);
        return redirect(route('login'));
    }
}
