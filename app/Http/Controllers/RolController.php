<?php

namespace App\Http\Controllers;

use App\Models\Rol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use JsValidator;

class RolController extends Controller
{
    protected $validationRules;
    protected $attributeNames;
    protected $errorMessages;
    protected $model;

    public function __construct(Rol $model)
    {
     $this->validationRules = [
                                'name' => 'bail|required|string|max:30|unique:segroles,name',
                              ];
       $this->attributeNames = [
                                 'name' => 'nombre del rol',
                               ];
       $this->errorMessages = [
                                'required' => 'El campo :attribute es obligatorio.',
                                'unique'=>'El :attribute ya se encuentra registrado.'
                             ];

      $this->model = $model;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $roles =  $this->setQuery($request)->paginate(25);

        return view('roles.index', compact('roles','request'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $rol = new Rol();
        $validator = JsValidator::make($this->validationRules, $this->errorMessages, $this->attributeNames, '#form');
        $accion = 'Agregar';

        return view('roles.form', compact('rol', 'validator','accion'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->setValidator($request)->validate();
        $rol = Rol::create($request->all());
        setMessage('EL registro ha sido agregado');

        return redirect('/rol');
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Rol  $rol
     * @return \Illuminate\Http\Response
     */
    public function show(Rol $rol)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Rol  $rol
     * @return \Illuminate\Http\Response
     */
    public function edit(Rol $rol)
    {
        $this->validationRules['name'] =['required','string','max:50','unique:roles,name,'.$rol->id];
        $validator = JsValidator::make($this->validationRules, $this->errorMessages, $this->attributeNames, '#form');
        $accion = 'Editar';

        return view('roles.form', compact('rol', 'validator','accion'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Rol  $rol
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Rol $rol)
    {
        $this->setValidator($request,$rol->id)->validate();
        $rol->update($request->all());
        setMessage('El registro ha sido actualizado');

        return redirect('/rol');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Rol  $rol
     * @return \Illuminate\Http\Response
     */
    public function destroy(Rol $rol)
    {
        $rol->delete();
        setMessage('El registro ha sido eliminado');

        return redirect('/rol');
    }

    protected function setValidator(Request $request, $id=0)
    {
        if($id!=0){
            $this->validationRules['name'] =['required','string','max:30','unique:segroles,name,'.$id];
        }

        return Validator::make($request->all(), $this->validationRules, $this->errorMessages)->setAttributeNames($this->attributeNames);
    }

    private function setQuery($request)
    {
        $query = $this->model;
        $query = $query->orderBy('id','DESC');

        if ($request->has('rol')) {
             $query = $query->where('name', 'LIKE', '%' . $request->input('rol') . '%');
        }

        return $query;
    }
}
