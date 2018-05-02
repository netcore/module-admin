<?php

namespace Modules\Admin\Http\Middleware\Admin;

use Closure;
use Illuminate\Http\Request;
use Modules\Admin\Models\IpWhitelist;

class Whitelist
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

        return $next($request);
    }
}
