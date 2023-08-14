<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use App\Models\CatalogoMunicipio;
use App\Models\Comparecencia;
use App\Models\Movimientos;
use App\Rules\DatosDetalle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ComparecenciaNotificacionController extends Controller
{
    public $model;
    protected $validationRules;
    protected $attributeNames;
    protected $errorMessages;

    public function __construct(Comparecencia $model)
    {
        $this->validationRules = [            
            'anexos' => 'required|string|min:2|max:2|in:Si,No',
            'copias_conocimiento' => 'required|string|min:2|max:2|in:Si,No',
        ];
        $this->attributeNames = [           
            'anexos' => '¿El requerimiento cuenta con anexos?',
            'copias_conocimiento' => '¿El requerimiento cuenta con copias de conocimiento?',
        ];
        $this->errorMessages = [
            'required' => 'El campo :attribute es obligatorio.',
            'before_or_equal' => 'El campo :attribute debe ser una fecha anterior o igual a hoy.',
            'publicacion_notificacion.required_if' => 'El campo :attribute es obligatorio cuando Notificación por estrados o edictos esta seleccionado.',
            'fecha_publicacion.required_if' => 'El campo :attribute es obligatorio cuando Notificación por estrados o edictos esta seleccionado.',
        ];

        $this->model = $model;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Comparecencia $comparecencia)
    {
        $auditoria = $comparecencia->auditoria;
        setSession('comparecencia_id', $comparecencia->id);       
        $catmunicipios = CatalogoMunicipio::orderBy('descripcion', 'asc')->get()->pluck('descripcion', 'descripcion')->prepend('Seleccionar municipio', '')->toArray();
        
        return view('comparecencianotificacion.form', compact('auditoria','comparecencia', 'catmunicipios'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comparecencia $comparecencia)
    {
        $this->setValidator($request, $comparecencia)->validate();
        $this->normalizarDatos($request);

        $request['usuario_modificacion_id'] = auth()->user()->id;
        $comparecencia->update($request->all());

        Movimientos::create([
            'tipo_movimiento' => 'Registro de la comparecencia',
            'accion' => 'Comparecencia',
            'accion_id' => $comparecencia->id,
            'estatus' => 'Aprobado',
            'usuario_creacion_id' => auth()->id(),
            'usuario_asignado_id' => auth()->id(),
        ]);          

        if (strlen($comparecencia->nivel_autorizacion) == 3) {
            $nivel_autorizacion = $comparecencia->nivel_autorizacion;
        } else {
            $nivel_autorizacion = substr(auth()->user()->unidad_administrativa_id, 0, 4);
        }

        $comparecencia->update(['fase_autorizacion' =>  'En validación', 'nivel_autorizacion' => $nivel_autorizacion]); 

        $titulo = 'Validación de los datos de comparecencia';
        $mensaje = '<strong>Estimado (a) ' . auth()->user()->director->name . ', ' . auth()->user()->director->puesto . ':</strong><br>
                    Ha sido registrada la comparecencia de la auditoría No. ' . $comparecencia->auditoria->numero_auditoria . ', por parte del ' . 
                    auth()->user()->puesto.' '.auth()->user()->name . ', por lo que se requiere realice la validación.';

        auth()->user()->insertNotificacion($titulo, $mensaje, now(), auth()->user()->director->unidad_administrativa_id,auth()->user()->director->id);

        setMessage('La comparecencia se ha registrado correctamente y se ha enviado a validación.');



        return redirect()->route('comparecencia.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    protected function setValidator(Request $request, Comparecencia $comparecencia = null)
    {
        $this->validationRules['anexos'] = ['required', 'string', 'min:2', 'max:2', 'in:Si,No', new DatosDetalle($comparecencia->comparecenciaAnexos->count())];
        $this->validationRules['copias_conocimiento'] = ['required', 'string', 'min:2', 'max:2', 'in:Si,No', new DatosDetalle($comparecencia->comparecenciaCopias->count())];

        return Validator::make($request->all(), $this->validationRules, $this->errorMessages)->setAttributeNames($this->attributeNames);
    }

    private function normalizarDatos(Request $request)
    {
        if ($request->notificacion_estrados == 'X') {
            $request['calle'] = null;
            $request['numero_domicilio'] = null;
            $request['colonia'] = null;
            $request['codigo_postal'] = null;
            $request['municipio'] = null;
            $request['entidad_federativa'] = null;
        }
        if ($request->notificacion_estrados != 'X') {
            $request['notificacion_estrados'] = null;
            $request['entidad_federativa'] = 'Estado de México';
        }

        return $request;
    }
}
