<?php

namespace App\Http\Controllers;

use App\Models\RemitentesFolio;
use Illuminate\Http\Request;
use App\Models\FolioCrr;
use App\Models\Auditoria;

class FolioAnexosController extends Controller
{
    protected $model;

    public function __construct(FolioCrr $model)
    {
      $this->model = $model;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
       $folios = $this->setQuery($request)->paginate(25);
        $auditoria = Auditoria::find(getSession('auditoria_id'));

        return view('foliosanexos.index', compact('auditoria','folios','request'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(FolioCrr $folio)
    {
        DD(2);
       
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show( $id)
    {
        //
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(FolioCrr $folio)
    {
        $auditoria = Auditoria::find(getSession('auditoria_id'));
        
        return view('foliosanexos.form', compact('auditoria','folio'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RemitentesFolio $remitente)
    {
        $folioscrr = FolioCrr::where('id', $remitente->folio_id)->first();
        $request['usuario_modificacion_id'] = auth()->id();
        $remitente->update($request->all());

        setMessage('El Remitente del Folio:'.$folioscrr->folio.' ha sido actualizado');

        return redirect()->route('remitentes.index');
		
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

    private function setQuery($request)
    {
        $query = $this->model;
        $query = $query->where('auditoria_id',getSession('auditoria_id'));
        $query = $query->orderBy('id','DESC');

        return $query;
    }

    public function exportar(){
        dd("export");
    }
}
