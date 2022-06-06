<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;

class JwtAuth
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
        $token = $request->header('access_token');
        $key = config('app.jwt_key');
        if (!$token) return response()->json(['message' => 'Anda Tidak Dapat Akses'], 401);

        try {
            $checkToken = JWT::decode($token, new Key($key, "HS256"));
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }

        $user = [
            'id' => $checkToken->id,
            'nama' => $checkToken->nama,
            'level' => $checkToken->level
        ];


        $request->request->add(['user' => $user]);
        return $next($request);
    }
}
