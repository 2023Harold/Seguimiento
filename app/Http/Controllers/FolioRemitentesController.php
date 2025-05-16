<?php

namespace App\Http\Controllers;

use App\Models\RemitentesFolio;
use Illuminate\Http\Request;
use App\Models\FolioCrr;
use App\Models\Auditoria;

class FolioRemitentesController extends Controller
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
    public function index()
    {
        //     
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(FolioCrr $folioscrr)
    {
        $folioremitente = new RemitentesFolio();
        $auditoria = Auditoria::find(getSession('auditoria_id'));

        return view('remitentes.form', compact('auditoria','folioscrr', 'folioremitente'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $folioscrr = FolioCrr::where('id', $request->folio_id)->first();
        $request['usuario_creacion_id'] = auth()->id();
        $folio  = RemitentesFolio::create($request->all());

        setMessage('El Remitente del Folio:'.$folioscrr->folio.' ha sido agregado');

        return redirect()->route('folioscrr.index');
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
    public function edit(Request $request, RemitentesFolio $remitente)
    {
        //dd($remitente);
        //$folioscrr = $remitentes = RemitentesFolio::where('id',$remitente->id)->get();
       
        $folioscrr = FolioCrr::where('id', $remitente->folio_id)->first();


        $folioremitente = $remitente;
        //dd($remitente, $folioscrr);
        $auditoria = Auditoria::find(getSession('auditoria_id'));
        //dd($remitente);
    

        return view('remitentes.form', compact('auditoria','remitente','folioscrr','folioremitente'));
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
        return redirect()->route('folioscrr.index');
		
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
}
