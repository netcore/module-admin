<?php

namespace Modules\Admin\Http\Middleware\Admin;

use Closure;
use Illuminate\Http\Request;

class canAuthorizeInAdmin
{
    protected $defaultAdminRole = 'admin';

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $userModel = config('auth.providers.users.model');
        $loginUsername = config('admin.login.username');

        //we allow to pass this athorization request only by registred users who has admin role
        if ($username = $request->get($loginUsername, null)) {
            if ($user = $userModel::where($loginUsername, '=', $username)->first()) {
                if ($user->hasPermission($request)) {
                    return $next($request);
                }
            }
        }

        return redirect()->route('admin::auth.login')->withError('This area is for administrators only');
    }
}
