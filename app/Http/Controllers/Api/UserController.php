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

        $users = $request->path() == 'api/get-chefs'
            ?  User::query()->where('rol_id', 2) 
            : User::query()->where('rol_id', 3);

        if(!empty(request('order_name')))  $users->orderBy('name', request('order_name'));

        // if(!empty(request('order_stock')))  $users->orderBy('stock', request('order_stock'));

        // return DataTables::of($users)->toJson();
        return DataTables::of($users)->filter(function ($query) {
            if (!empty(request('filter_name'))) {
              $query->where('name','like','%'.request('filter_name').'%');
            }
          })->toJson();
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
                'user_address'  => $request->user_address,
            ]);
        } catch (Exception $th) {
            return $this->returnSuccess(400, $th->getMessage() );
        }

        return $this->returnSuccess(200, $newUser);
        

    }
    public function updateUser(Request $request, $id){
        $validated = $this->validateFieldsFromInput($request->all(), 'update') ;

        if (count($validated) > 0) return $this->returnFail(400, $validated[0]);
       
        $user = User::find($id);
        if (!$user) return $this->returnFail(400, 'Usuario no encontrado');
        
        try {
            $user->name = $request->name;
            $user->email = $request->email;
            $user->user_address = $request->user_address;
            $user->password = !empty($request->password) 
            ? Hash::make($request->password)
            : $user->password;


            $user->save();
            
        } catch (Exception $th) {
            return $this->returnSuccess(400, $th->getMessage() );
        }

        return $this->returnSuccess(200, $user);
        

    }
    public function deleteUser($userId)
    {
        if (!$userId) {
            return $this->returnFail(400, "El ID del producto es requerido.");
        }

        $user = User::find($userId);
        // $dismantlingsOfProducts= Dismantling::where('piece_product_id', $userId)->delete();


        if (!$user) {
            return $this->returnFail(404, "Producto no encontrada.");
        }

        $user->delete();


        return $this->returnSuccess(200, ['id' => $userId, 'deleted_at' => $user->deleted_at]);
    }
    public function getUserById($userId){
        $user = User::with('rol', 'orders')->find($userId);
 
         return $this->returnSuccess(200, $user);
     }
    public function getAlluserByRol(Request $request){
       $usersByRol = User::with('rol')->where('rol',$request->rol)->get();

        return $this->returnSuccess(200, $usersByRol);
    }
    private function validateFieldsFromInput($inputs, $type = 'new'){
        if($type != 'new'){
            $rules=[
                'name'          => ['required', 'regex:/^[a-zA-Z-À-ÿ .]+$/i'],
                'email'         => ['required', 'email',],
                'password'      => ['min:8'],
                'user_address'  => ['required'],
            ];
            $messages = [
                'name.required'         => 'El nombre es requerido.',
                'name.regex'            => 'Nombre no valido',
                'email.required'        => 'El email es requerido.',
                'email.email'           => 'El Email no es valido',
                'user_address.required' => 'La dirección es requerida.',
                'password.min'          => 'La contraseña debe tener un minimo de 8 caracteres'
            ];
    
    
             $validator = Validator::make($inputs, $rules, $messages)->errors();
    
            return $validator->all() ;

        }


        $rules=[
            'name'          => ['required', 'regex:/^[a-zA-Z-À-ÿ .]+$/i'],
            'email'         => ['required', 'email', 'unique:users'],
            'password'      => ['required', 'min:8'],
            'rol_id'        => ['required'],
            'user_address'  => ['required'],
        ];
        $messages = [
            'name.required'         => 'El nombre es requerido.',
            'email.required'        => 'El email es requerido.',
            'user_address.required' => 'La dirección es requerida.',
            'email.unique'          => 'Email ya registrado.',
            'password.required'     => 'La contraseña es requerido.',
            'rol_id.required'       => 'El rol es requerido.',
            'password.min'          => 'La contraseña debe tener un minimo de 8 caracteres',
            'email.email'           => 'El Email no es valido'
        ];


         $validator = Validator::make($inputs, $rules, $messages)->errors();

        return $validator->all() ;
    }

}
