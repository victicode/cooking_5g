<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function getUser(Request $request){
       
        return response()->json([ 'data'=>['code'=>200,'user' => User::with('rol')->find($request->user()->id)]], 200);

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
                'user_address'  => $request->rol_id == 3 ? $request->user_address : ' ',
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
    private function validateRequiredFields($inputRequest)
    {

 
        
        if (!array_key_exists('name', $inputRequest) && empty($inputRequest['name']) )  {
            return [
                'validated' => false,
                'message' => 'El nombre del usuario requerido.',
            ];
        }
        if (!array_key_exists('email', $inputRequest) && empty($inputRequest['email']) ) {
            return [
                'validated' => false,
                'message' => 'El email del usuario es requerido',
            ];
        }
        if (!array_key_exists('short_description', $inputRequest) && empty($inputRequest['short_description']) ){
            return [
                'validated' => false,
                'message' => 'La contraseña requerida.',
            ];
        }
        if ( !array_key_exists('initial_stock', $inputRequest) && empty($inputRequest['initial_stock'])){ 
            return [
                'validated' => false,
                'message' => 'El stock inicial es requerido.',
            ];
        }
        if (!array_key_exists('type_unit', $inputRequest) && empty($inputRequest['type_unit'])) {
            return [
                'validated' => false,
                'message' => 'El tipo de unidad es requerido.',
            ];
        }
        if (!array_key_exists('is_dismantling', $inputRequest) ){
            return [
                'validated' => false,
                'message' => 'Se requiere que especifique si trae despecies.',
            ];
        }

        return [
            'validated' => true,
        ];
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
