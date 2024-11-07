<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;
use App\Mail\ResetPassword;
use App\Mail\SetPassword;
use App\Mail\SendMailRegistro;
use App\Models\User;
use App\Models\Rol;
use App\Models\CatalogoUnidadesAdministrativas;
use App\Models\Entidad_Fiscalizable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use JsValidator;
use App\Mail\SendAviso;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Password;

class UsersController extends Controller
{
    protected $validationRules;
    protected $attributeNames;
    protected $errorMessages;
    protected $model;

    public function __construct(User $model)
    {
        $this->validationRules = [
            'name' => 'bail|required|max:75|string|regex:/^[A-Z,a-zñáéíóúü\s]+$/i',
            'puesto' => 'required|string|max:150|regex:/^[A-Z,a-zñáéíóúü"\s]+$/i',            
            'rol' => 'required|max:40|string',
        ];

       $this->attributeNames = [
            'name' => 'Nombre del usuario',
            'puesto' => 'Puesto',
            'email' => 'Correo electrónico',
            'rol' => 'Rol',
            'unidad_administrativa_id' => 'Unidad administrativa',
            'entidad_fiscalizable_id' => 'Entidad fiscalizable',
        ];
       $this->errorMessages = [
            'required' => 'El campo :attribute es obligatorio.',
            'unique'=>'El :attribute ya se encuentra registrado.'
        ];
      $this->model = $model;
    }

    public function index(Request $request)
    {
        $users = $this->setQuery($request)->paginate(20);
        return view('users.index',compact('users','request'));
    }

    public function create()
    {
		 $user = new User();		 
         $rol = new Rol();		 
         $unidad = new CatalogoUnidadesAdministrativas();		 
         $roles =  $rol->getRoles()->prepend('Seleccione el rol','');		
         $accion = 'Agregar';
         $unidadesadministrativas = $unidad->getUnidadesAdministrativas()->prepend('Seleccione la unidad administrativa','');
		 
         /*$entidades_fiscalizables = Entidad_fiscalizable::orderBy('ambito_id')
             ->get()->pluck('descripcion','id')->prepend('Seleccione la entidad fiscalizable','');*/
			 

         $validator = JsValidator::make($this->validationRules, $this->errorMessages, $this->attributeNames, '#form');		 
		
		
         return view('users.form',compact('user','roles','unidadesadministrativas','validator','accion'));
    }

    public function store(Request $request)
    {
         //$this->setValidator($request)->validate();

         $request = $this->fixData($request);
        // //$request['password'] = Hash::make(Str::random(12));
         $request['password'] = Hash::make('password');
         $request['estatus']  = 'Activo';
         $request['usuario_creacion_id'] = auth()->id();
         $user = User::create($request->all());
         $user->syncRoles($request->rol);
        // $token = Str::random(60);
        // $user['token'] = $token;
        // $user['is_verified'] = 0;
        // $user->save();
        // Mail::to($request->email)->send(new SetPassword($user->name, $token));
         setMessage('El usuario ha sido agregado y se han enviado las instrucciones por correo');
         return redirect('/user');
    }

    public function setpassword(User $user)
    {
        // $validator = JsValidator::make([
        //     'password' => 'required|confirmed|min:10|max:60|string',
        //     'password_confirmation'=> 'required|min:10|max:60|string',
        // ], $this->errorMessages,[
        //     'password' => 'contraseña',
        //     'password_confirmation'=> 'confirmar contraseña',
        // ], '#form');
        // return view('users.setpassword', compact('user', 'validator'));
    }

    public function notificacion(User $user)
    {
        // $user->update(['fecha_envio_correo'=>now()]);
        // /*Mail::to($user->email)
        //     ->send(new SendMail($user));*/
        // $token = Str::random(60);
        // $user['token'] = $token;
        // $user['is_verified'] = 0;
        // $user->save();
        // Mail::to($user->email)->send(new ResetPassword($user->name, $token));
        // setMessage('Las instrucciones para accesar el sistema ha sido enviado al correo electrónico');
        // return redirect('/user');
    }

    public function edit(User $user)
    {
        // $unidad= new Unidad_Administrativa();
        // $rol = new Rol();
        // $roles = $rol->getRoles();
        // $unidadesadministrativas = $unidad->getUnidadesAdministrativas();
        // $accion = 'Editar';
        // $entidades_fiscalizables = Entidad_fiscalizable::orderBy('ambito_id')->get()->pluck('descripcion','id');
        // $this->validationRules['email'] =['required','string','email','max:60','unique:users,email,'.$user->id];
        // $validator = JsValidator::make($this->validationRules, $this->errorMessages, $this->attributeNames, '#form');
        // return view('users.form',compact('user', 'roles', 'unidadesadministrativas', 'entidades_fiscalizables', 'validator', 'accion'));
    }

    public function update(Request $request, User $user)
    {
        // if(isset($request->_setpassword) and $request->_setpassword == '1') {
        //     //$this->setValidator($request)->validate();
        //     $request = $this->fixData($request);
        //     $user->update($request->all());
        //     setMessage('El usuario ha sido actualizado');
        //     return redirect('/');
        // }

        // $this->setValidator($request,$user->id)->validate();
        // $request = $this->fixData($request);
        // $request['usuario_actualizacion_id'] = auth()->id();
        // $user->update($request->all());
        // $user->syncRoles($request->rol);
        // setMessage('El usuario ha sido actualizado');

        // return redirect('/user');
    }

    public function updatepassword(Request $request, User $user)
    {
        // $request['password'] = Hash::make($request['password']);
        // $request['is_verified'] = 1;
        // $user->update($request->all());
        // setMessage('El usuario ha sido actualizado');
        // return redirect('/');
    }

    public function destroy(User $user)
    {
        // $user->delete();
        // setMessage('El usuario ha sido eliminado');

        // return redirect('/user');
    }

    protected function setValidator(Request $request, $id=0)
    {
        // if ($id !=0 ) {
        //     $this->validationRules['email'] =['required','email','max:60','unique:users,email,'.$id];
        // }
        // if(isset($request->_setpassword) and $request->_setpassword == '1'){
        //     $this->validationRules = [
        //         'password' => 'required|confirmed|min:10|max:60|string',
        //         'password_confirmation'=> 'required|min:10|max:60|string',
        //     ];
        //     $this->attributeNames = [
        //         'password' => 'contraseña',
        //         'password_confirmation'=> 'confirmar contraseña',
        //     ];
        // }
        // if ($request->rol=='Entidad Fiscalizable'){
        //     $this->validationRules = array_merge($this->validationRules, [
        //         'entidad_fiscalizable_id' => 'required|integer|exists:FISCatEntidad_fiscalizables,id',
        //     ]);
        // } else {
        //     $this->validationRules = array_merge($this->validationRules, [
        //         'unidad_administrativa_id' => 'required|integer|exists:FISCatUnidad_administrativas,id',
        //     ]);
        // }
        // return Validator::make($request->all(), $this->validationRules, $this->errorMessages)->setAttributeNames($this->attributeNames);
    }

    private function setQuery($request)
    {
        $query = $this->model;
        $query = $query->orderBy('id','DESC');
        if ($request->filled('name')) {
             $query = $query->whereLike('name', $request->name);
        }
        if($request->filled('email')){
            $query = $query->whereLike('email',$request->email);
        }
        if($request->filled('estatus')&& $request->input('estatus') != 'Todas') {
            $query = $query->whereLike('estatus', $request->input('estatus'));
        }
        return $query;
    }

    private function fixData(Request $request)
    {
        /*
        if (isset($request->password) & !empty ($request->password)) {
            $request[ 'password' ] = Hash::make($request->password);
        }
        */
         if ($request->rol == 'Entidad Fiscalizable'){
             $request['unidad_administrativa_id'] = null;
         } else{
             $request['entidad_fiscalizable_id'] = null;
         }
         return $request;
    }

    public function forgotPasswordValidate($token)
    {
        // $user = User::where('token', $token)->where('is_verified', 0)->first();
        // if ($user) {
        //     $datos['is_verified'] = 1;
        //     $user->update($datos);
        //     $email = $user->email;
        //     $validator = JsValidator::make([
        //         'password' => 'required|confirmed|min:10|max:60|string',
        //         'password_confirmation'=> 'required|min:10|max:60|string',
        //     ], $this->errorMessages,[
        //         'password' => 'contraseña',
        //         'password_confirmation'=> 'confirmar contraseña',
        //     ], '#form');
        //     return view('users.setpassword', compact('email', 'user', 'validator'));
        // }
        // return view('users.expired-reset');
    }
}
