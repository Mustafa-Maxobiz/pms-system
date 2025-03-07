<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ActivityLogger
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        activity('request')
            ->causedBy(auth()->user() ?? null) // ✅ Agar user logged in hai to uski info save hogi
            ->withProperties([
                'method'     => $request->method(),
                'url'        => $request->fullUrl(),
                'ip'         => $request->ip(),
                'user_agent' => $request->header('User-Agent'),
            ])
            ->log('Visited: ' . $request->path());

        return $response;
    }
}
