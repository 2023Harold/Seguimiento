<?php

namespace App\Http\Controllers;

use App\Models\CatalogoMunicipio;
use App\Models\ComparecenciaCopia;
use Illuminate\Http\Request;

class ComparecenciaCopiaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $copias = $this->setQuery($request)->paginate(50);

        return view('comparecenciascopias.index', compact('copias', 'request'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $copia = new ComparecenciaCopia();
        $catmunicipios = CatalogoMunicipio::orderBy('descripcion', 'asc')->get()->pluck('descripcion', 'descripcion')->prepend('Seleccionar municipio', '')->toArray();
      
        $accion = 'Agregar';
        return view('comparecenciascopias.form', compact('copia', 'accion', 'catmunicipios'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->merge([
            'comparecencia_id' => getSession('comparecencia_id'),
            'entidad_federativa' => 'Estado de México',
        ]);
        $request = $this->normalizarDatos($request);
        ComparecenciaCopia::create($request->all());
        $this->actualizaProgresivo();
        setMessage('El registro ha sido agregado');

        return redirect()->route('comparecenciacopia.index');
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
    public function edit(ComparecenciaCopia $copia)
    {
       
        $catmunicipios = CatalogoMunicipio::orderBy('descripcion', 'asc')->get()->pluck('descripcion', 'descripcion')->prepend('Seleccionar municipio', '')->toArray();
        $accion = 'Editar';

        return view('comparecenciascopias.form', compact('copia', 'accion', 'catmunicipios'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ComparecenciaCopia $copia)
    {
        $request->merge([
            'entidad_federativa' => 'Estado de México',
        ]);
        $request = $this->normalizarDatos($request);
        $copia->update($request->all());
        setMessage('Se han modificado los datos correctamente');

        return redirect()->route('comparecenciacopia.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ComparecenciaCopia $copia)
    {
        $copia->delete();
        $this->actualizaProgresivo();
        setMessage('El registro ha sido eliminado');

        return redirect()->route('comparecenciacopia.index');
    }

    private function setQuery($request)
    {
        $query = new ComparecenciaCopia();
        $query = $query->where('comparecencia_id', getSession('comparecencia_id'))->orderBy('numero');

        return $query;
    }

    public function actualizaProgresivo()
    {
        $numeroSiguiente = 1;
        $modelName = new ComparecenciaCopia();
        $er_records = $modelName::where('comparecencia_id', getSession('comparecencia_id'));
        $er_records = $er_records->orderBy('id')->get();
        foreach ($er_records as $er_record) {
            $er_record->update(['numero' => $numeroSiguiente]);
            $numeroSiguiente++;
        }
    }

    private function normalizarDatos(Request $request)
    {
        if ($request->domicilio_notificacion == 'Si') {
            $request['calle'] = null;
            $request['numero_domicilio'] = null;
            $request['colonia'] = null;
            $request['codigo_postal'] = null;
            $request['municipio'] = null;
            $request['entidad_federativa'] = null;
        }
        if ($request->domicilio_notificacion != 'Si') {
            $request['domicilio_notificacion'] = null;
        }

        return $request;
    }
}
