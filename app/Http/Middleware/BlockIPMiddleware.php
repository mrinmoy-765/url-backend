<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BlockIPMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $ip = $request->ip();
        $key = 'ip_request_count:' . $ip;

        $requestCount = Cache::get($key, 0);
        $requestCount++;

        Cache::put($key, $requestCount, now()->addMinutes(1));

        if ($requestCount > 3) {
            // Block the IP for 5 minutes
            Cache::put('blocked_ip:' . $ip, true, now()->addMinutes(5));
            return response()->json(['error' => 'IP blocked for 5 minutes.'], 403);
        }
        return $next($request);
    }
}
