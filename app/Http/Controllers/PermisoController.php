<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Permiso;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;
use JsValidator;

class PermisoController extends Controller
{
    protected $validationRules;
    protected $attributeNames;
    protected $errorMessages;
    protected $model;

    public function __construct(Permiso $model)
    {

        $this->validationRules = [
                                'name' => 'bail|required|string|max:50|unique:segpermissions,name',
                                ];
        $this->attributeNames = [
                                'name' => 'nombre del permiso',
                                ];
       $this->errorMessages   = [
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
        $permisos = $this->setQuery($request)->paginate(25);
        return view('permisos.index', compact('permisos','request'));
    }

    public function create()
    {
        $permiso = new Permiso();
        $validator = JsValidator::make($this->validationRules, $this->errorMessages, $this->attributeNames, '#form');
        $accion = 'Agregar';

        return view('permisos.form', compact('permiso', 'validator','accion'));
    }

    public function store(Request $request)
    {
        $this->setValidator($request)->validate();
        $permiso = Permiso::create($request->all());
        setMessage('El registro ha sido agregado');

        return redirect('/permiso');
    }

    public function show(Permission $permiso)
    {
        return "";
    }

    public function edit(Permiso $permiso)
    {
        $this->validationRules['name'] =['required','string','max:50','unique:permissions,name,'.$permiso->id];
        $validator = JsValidator::make($this->validationRules, $this->errorMessages, $this->attributeNames, '#form');
        $accion = 'Editar';

        return view('permisos.form', compact('permiso', 'validator','accion'));
    }

    public function update(Request $request,Permiso $permiso)
    {
        $this->setValidator($request,$permiso->id)->validate();
        $permiso->update($request->all());
        setMessage('El registro ha sido actualizado');

        return redirect('/permiso');
    }

    public function destroy(Permiso $permiso)
    {
        $permiso->delete();
        setMessage('El registro ha sido eliminado');

        return redirect('/permiso');
    }

    protected function setValidator(Request $request, $id=0)
    {
        if($id!=0){
        $this->validationRules['name'] =['required','string','max:50','unique:segpermissions,name,'.$id];
        }

        return Validator::make($request->all(), $this->validationRules, $this->errorMessages)->setAttributeNames($this->attributeNames);
    }

    private function setQuery($request)
    {
        $query = $this->model;
        $query = $query->orderBy('id','DESC');
        if ($request->has('permiso')) {
            $query = $query->whereLike('name', $request->permiso);
        }

        return $query;
    }

}
