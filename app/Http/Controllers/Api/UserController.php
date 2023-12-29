<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Rol;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;



class UserController extends Controller
{
    public function getAllclient(Request $request){
        return $this->returnSuccess(200, User::where('rol_id', 3)->get());
    }
    public function getUserTableByRol(Request $request){

        $users = $request->path() == 'api/get-chefs' ?  User::query()->where('rol_id', 2) : User::query()->where('rol_id', 3);

        // if(!empty(request('order_title')))  $products->orderBy('title', request('order_title'));

        // if(!empty(request('order_stock')))  $products->orderBy('stock', request('order_stock'));

        return DataTables::of($users)->toJson();
        // return DataTables::of($products)->filter(function ($query) {
        //     if (!empty(request('filter_product_title'))) {
        //       $query->where('title','like','%'.request('filter_product_title').'%');
        //     }
        //   })->toJson();
    }
    public function createUser(Request $request){
       
        $validated = $this->validateFieldsFromInput($request->all()) ;

        if (count($validated) > 0) return $this->returnFail(400, $validated[0]);
        
        try {
            $newUser = User::create([
                'name'          => $request->name,
                'email'         => $request->email,
                'password'      => Hash::make($request->password),
                'rol_id'        => $request->rol_id,
                'user_address'  => $request->rol_id == 3 ? $request->user_address : '----',
            ]);
        } catch (Exception $th) {
            return $this->returnSuccess(400, $th->getMessage() );
        }

        return $this->returnSuccess(200, $newUser);
    }
    public function getAlluserByRol(Request $request){
       $usersByRol = User::with('rol')->where('rol',$request->rol)->get();

        return $this->returnSuccess(200, $usersByRol);
    }
    private function validateFieldsFromInput($inputs){
        $rules=[
            'name'          => ['required', 'regex:/^[a-zA-Z-À-ÿ .]+$/i'],
            'email'         => ['required', 'email', 'unique:users'],
            'password'      => ['required', 'min:8'],
            'rol_id'        => ['required'],
        ];
        $messages = [
            'name.required'     => 'El nombre es requerido.',
            'name.required'     => 'El nombre es invalido.',
            'email.required'    => 'La contraseña es requerido.',
            'email.unique'      => 'Email ya regsitrado.',
            'password.required' => 'La contraseña es requerido.',
            'rol_id.required'   => 'El rol es requerido.',
            'password.min'      => 'La contraseña debe tener un minimo de 8 caracteres',
            'email.email'       => 'El Email no es valido'
        ];


         $validator = Validator::make($inputs, $rules, $messages)->errors();

        return $validator->all() ;
    }

}
