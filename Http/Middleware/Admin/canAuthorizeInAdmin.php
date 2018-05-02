<?php

namespace Modules\Admin\Http\Middleware\Admin;

use Closure;
use Illuminate\Http\Request;
use Modules\Admin\Models\IpWhitelist;

class canAuthorizeInAdmin
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
        $userModel = config('auth.providers.users.model');
        $loginUsername = config('admin.login.username');
        $username = $request->get($loginUsername, null);

        // IP whitelist check
        if (config('netcore.module-admin.whitelist.enabled')) {
            $ips = IpWhitelist::pluck('ip')->toArray();
            $whitelisted = checkWhitelistIp($request->ip(), $ips);

            if (!$whitelisted && $ip = config('netcore.module-admin.whitelist.fallback_ip')) {
                $whitelisted = $request->ip() === $ip;
            }

            if (!$whitelisted) {
                abort(404);
            }
        }

        $user = $userModel::where($loginUsername, '=', $username)->first();

        // We allow to pass this authorization request only by registred users who has admin role
        if (!$user || !$user->hasPermission($request)) {
            return redirect()->route('admin::auth.login')->withError('This area is for administrators only');
        }

        return $next($request);
    }
}
