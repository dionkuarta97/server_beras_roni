<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;

class AdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request->get('user');

        if ($user['level'] != 1) return response()->json(['message' => 'Anda Tidak Dapat Akses'], 401);

        return $next($request);
    }
}
