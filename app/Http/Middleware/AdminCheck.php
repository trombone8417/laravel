<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AdminCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        
        if ($request->path()=='app/admin_login') {
            return $next($request);
        }
        if (!Auth::check()) {
            return response()->json([
                'msg' => 'You are not allowed to access this route...',
                'url' => $request->path()
            ], 403);
        }
        $user = Auth::user();
        if ($user->userType == 'User') {
            return response()->json([
                'msg' => 'You are not allowed to access this route...',

            ], 403);
        }
        return $next($request);
    }
}
