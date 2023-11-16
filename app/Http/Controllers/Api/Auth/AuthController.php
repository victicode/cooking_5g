<?php

namespace App\Http\Controllers\Api\Auth;

use Illuminate\Http\Request;

use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    //
    public function login(Request $request){
			// echo 'nada que ver';
			$validator = Validator::make($request->all(), [
					'email'=> 'required',
					'password'=>'required'
			]);
			if($validator->fails()){
					return response()->json($validator->errors(),442);
			}
			if(! $token = JWTAuth::attempt($validator->validate())){
					return response()->json(['data' => ['code' => 500, 'messagge'=> 'Estas credenciales no coinciden con nuestros registros.']],403);
			}
			return response()->json([ 'data'=>['code'=>200,'access_token' => $token]], 200);
    }
		public function logout(Request $request)
    {
			auth()->logout();
			return response()->json([ 'data'=>['code'=>200]], 200);

    }
    protected function createNewToken($token){
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'user' => auth()->user()
        ]);
    }
		public function getUser(Request $request){
			// $this->createNewToken(auth()->refresh());
			return response()->json([ 'data'=>['code'=>200,'user' => User::with('rol')->find($request->user()->id)]], 200);

	}
	// public function refresh(){
	// 		return $this->createNewToken(auth()->refresh());
	// }
}
