<?php

namespace Modules\Admin\Http\Middleware\Admin;

use Closure;
use Illuminate\Http\Request;

class isAdmin
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            return redirect()->route('admin::auth.login');
        }


        if (auth()->check()) {
            if (auth()->user()->hasPermission($request)) {
                return $next($request);
            }

            return redirect()->route('admin::permission.access-denied');
        }

    }
}
