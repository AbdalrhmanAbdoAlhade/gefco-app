<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role)
    {
        // التحقق من تسجيل الدخول ومن الرول
        if ($request->user() && $request->user()->role === $role) {
            return $next($request);
        }

        return response()->json(['message' => 'غير مصرح لك بالدخول'], 403);
    }
}
