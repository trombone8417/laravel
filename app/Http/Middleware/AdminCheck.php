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
        // 在 storage\logs\laravel.log 產生log檔
        Log::info('inside middlware');

        if (!Auth::check()) {
            return response()->json(['msg'=>'You are not allowed to access this route...'], 402);
        }
        $user = Auth::user();
        if ($user->userType=='User') {
            return response()->json(['msg'=>'You are not allowed to access this route...'], 402);
        }
        return $next($request);
    }
}
