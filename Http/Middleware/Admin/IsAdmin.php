<?php

namespace Modules\Admin\Http\Middleware\Admin;

use Closure;
use Illuminate\Http\Request;
use Modules\Admin\Models\IpWhitelist;

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
            // IP whitelist check
            $whitelisted = true;
            if (config('netcore.module-admin.whitelist.enabled')) {
                $ips = IpWhitelist::pluck('ip')->toArray();
                $whitelisted = checkWhitelistIp($request->ip(), $ips);

                if (!$whitelisted && $ip = config('netcore.module-admin.whitelist.fallback_ip')) {
                    $whitelisted = $request->ip() === $ip;
                }
            }

            if (auth()->user()->hasPermission($request) && $whitelisted) {
                return $next($request);
            }

            return redirect()->route('admin::auth.login')->withError('This area is for administrators only');
        }

    }
}
