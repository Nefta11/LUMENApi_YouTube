<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Models\User;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Hash;

class AuthController extends BaseController
{
    private $request;

    public function __construct(Request $request){
        $this->request = $request;
    }

    public function jwt(User $user){
        $payload = [
            "iss" => "api-youtube-jwt",
            "sub" => $user->id,
            "iat" => time(),
            "exp" => time() + 60*60,
        ];
        return JWT::encode($payload, env('JWT_SECRET'), 'HS256');
    }

    public function authenticate(){
        $this->validate($this->request, [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $this->request->input('email'))->first();

        if (!$user) {
            return response()->json([
                'error' => "El correo no existe"
            ], 404);
        }

        if (Hash::check($this->request->input('password'), $user->password)) {
            return response()->json([
                'token' => $this->jwt($user)
            ], 200);
        }

        return response()->json([
            'error' => "El correo o la contraseña están incorrectos"
        ], 401);
    }
}
