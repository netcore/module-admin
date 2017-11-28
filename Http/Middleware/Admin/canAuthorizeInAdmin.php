<?php

namespace Modules\Admin\Http\Middleware\Admin;

use Closure;
use Illuminate\Http\Request;
use Modules\Admin\Models\IpWhitelist;

class canAuthorizeInAdmin
{
    /**
     * @var string
     */
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

        // We allow to pass this authorization request only by registred users who has admin role
        if ($username = $request->get($loginUsername, null)) {
            if ($user = $userModel::where($loginUsername, '=', $username)->first()) {

                // IP whitelist check
                $whitelisted = true;
                if (config('netcore.module-admin.whitelist.enabled')) {
                    $ips = IpWhitelist::pluck('ip')->toArray();
                    $whitelisted = checkWhitelistIp($request->ip(), $ips);

                    if (!$whitelisted && $ip = config('netcore.module-admin.whitelist.fallback_ip')) {
                        $whitelisted = $request->ip() === $ip;
                    }
                }

                if ($user->hasPermission($request) && $whitelisted) {
                    return $next($request);
                }
            }
        }

        return redirect()->route('admin::auth.login')->withError('This area is for administrators only');
    }
}
