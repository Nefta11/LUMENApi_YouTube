<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\ExperideException;

class JwtMiddleware
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
        if(!$request-> header('Authorization')){
            return response()->json([
                'error'=> 'Se requiere el token'

            ], 401);
        }

        $array_token = explode('', $request->header('Authorization'));

        $response = $next($request);

        // Post-Middleware Action

        return $response;
    }
}