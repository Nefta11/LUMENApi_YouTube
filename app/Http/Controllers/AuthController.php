<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Models\User;
use Firebase\JWT\JWT;

class Controller extends BaseController
{
    private $request;

    public function _construct(Request $request){
        $this->request = $request;
    }

    public function jwt(User $user){
        $payload =[
            "iss" => "api-youtube-jwt",
            "sub"=> $user->id,
            "iat"=> time(),
            "exp"=> time() + 60 * 60,
        ];
        return JWT::encode($payload, env('JWT_SECRET'),'HS256');
    }

    public function authenticate (User $user){
        $this->validate($this->request, [
            'mail'=>'required|email',
            'password'=>'required'
        ]);

        $user = User:: where('email',$this->request->get->input('email'))->first();
        if(!$user){
            return response()-> json([
                'error'=> "El correo no existe"
            ], 400);
        }
        if($this->request->input('password')== $user->password){
            return response()->json([
                'token'=>$this -> jwt($user)
            ],200);        
        }
        return response()->json([
            'error'=> "El correo o el pasword estan incorrectos"
        ], 400);
    }
}
