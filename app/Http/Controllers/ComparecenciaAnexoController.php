<?php

namespace App\Http\Controllers;

use App\Models\Comparecencia;
use App\Models\ComparecenciaAnexo;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ComparecenciaAnexoController extends Controller
{
    protected $model;

    public function __construct(ComparecenciaAnexo $model)
    {
        $this->validationRules = [
            'archivo' => 'required',
            'descripcion' => 'required|string|max:800',
        ];
        $this->attributeNames = [
            'archivo' => 'anexo',
            'descripcion' => 'descripción',
        ];
        $this->errorMessages = [
            'required' => 'El campo :attribute es obligatorio.',
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
        $anexos = $this->setQuery($request)->paginate(50);

        return view('comparecenciasanexos.index', compact('anexos', 'request'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $anexo = new ComparecenciaAnexo();
             
        $accion = 'Agregar';

        return view('comparecenciasanexos.form', compact('anexo', 'accion'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $comparecencia = Comparecencia::find(getSession('comparecencia_id'));
        
        $request->merge([
            'comparecencia_id' => getSession('comparecencia_id'),
        ]);
        $ruta = env('APP_RUTA_MINIO').'Auditorias/' . strtoupper(Str::slug($comparecencia->auditoria->numero_auditoria)).'/Documentos';
        //mover_archivos_minio($request, ['archivo'], null, $ruta);
        mover_archivos($request, ['archivo']);
        ComparecenciaAnexo::create($request->all());
        $this->actualizaProgresivo();
        setMessage('El registro ha sido agregado');

        return redirect()->route('comparecenciaanexo.index');
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
    public function edit(ComparecenciaAnexo $anexo)
    {       
        $accion = 'Editar';       

        return view('comparecenciasanexos.form', compact('anexo', 'accion'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ComparecenciaAnexo $anexo)
    {
        $ruta = env('APP_RUTA_MINIO').'Auditorias/' . strtoupper(Str::slug($anexo->comparecencia->auditoria->numero_auditoria)).'/Documentos';
        mover_archivos($request, ['archivo'],$anexo);
        //mover_archivos_minio($request, ['archivo'], $anexo, $ruta);
        $anexo->update($request->all());
        setMessage('Se han modificado los datos correctamente');

        return redirect()->route('comparecenciaanexo.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ComparecenciaAnexo $anexo)
    {
        Storage::cloud()->delete($anexo->archivo);
        $anexo->delete();
        $this->actualizaProgresivo();
        setMessage('El registro ha sido eliminado');

        return redirect()->route('comparecenciaanexo.index');
    }

    private function setQuery($request)
    {
        $query = $this->model;
        $query = $query->where('comparecencia_id', getSession('comparecencia_id'))->orderBy('numero');

        return $query;
    }

    public function actualizaProgresivo()
    {
        $numeroSiguiente = 1;
        $modelName = $this->model;
        $er_records = $modelName::where('comparecencia_id', getSession('comparecencia_id'));
        $er_records = $er_records->orderBy('id')->get();
        foreach ($er_records as $er_record) {
            $er_record->update(['numero' => $numeroSiguiente]);
            $numeroSiguiente++;
        }
    }

}
