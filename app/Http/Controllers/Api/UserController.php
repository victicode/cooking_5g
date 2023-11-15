<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getUser(Request $request){
       
        return response()->json([ 'data'=>['code'=>200,'user' => User::with('rol')->find($request->user()->id)]], 200);

    }
}
<