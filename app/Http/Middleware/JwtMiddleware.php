<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException; // Corregir el nombre de la excepción
use Firebase\JWT\Key; // Asegúrate de importar la clase Key correctamente

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
        // Verificar que el encabezado de Autorización esté presente
        if (!$request->header('Authorization')) {
            return response()->json([
                'error' => 'Se requiere el token'
            ], 401);
        }

        // Dividir el encabezado de autorización para obtener el token
        $array_token = explode(' ', $request->header('Authorization'));
        if (count($array_token) < 2) {
            return response()->json(['error' => 'Formato de token inválido'], 400);
        }
        $token = $array_token[1];

        try {
            // Decodificar el token usando la clave secreta y el algoritmo HS256
            $credentials = JWT::decode($token, new Key(env('JWT_SECRET'), 'HS256'));
        } catch (ExpiredException $e) {
            return response()->json([
                'error' => 'El token ha expirado'
            ], 400);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Algo ha ocurrido al decodificar el token'
            ], 400);
        }

        // Buscar al usuario basado en el 'sub' del token
        $user = User::find($credentials->sub);
        if (!$user) {
            return response()->json([
                'error' => 'Usuario no encontrado'
            ], 404);
        }

        // Agregar usuario al request para su uso posterior en el controlador
        $request->auth = $user;

        return $next($request);
    }
}
