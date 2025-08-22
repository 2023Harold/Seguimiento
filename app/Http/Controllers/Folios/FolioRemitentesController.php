<?php

namespace App\Http\Controllers\Folios;

use App\Http\Controllers\Controller;
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
    public function index(Request $request)
    {
        $auditoria = Auditoria::find(getSession('auditoria_id'));
        $folioscrr = FolioCrr::find(getSession('folio_id_session'));
        $remitentes = RemitentesFolio::where('folio_id',$folioscrr->id)->get();
        //dd($folioscrr, $remitentes );
        //dd($request->folio); //si obtengo el folio
        return view('folios.remitentes.index', compact('folioscrr', 'auditoria','remitentes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(FolioCrr $folioscrr)
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $auditoria = Auditoria::find(getSession('auditoria_id'));
        $folioscrr = FolioCrr::where('id', $request->folio_id)->first();
        $request['usuario_creacion_id'] = auth()->id();
        $remitentes = RemitentesFolio::where('folio_id',$folioscrr->id)->get();
        
        $folio  = RemitentesFolio::create($request->all());

        setMessage('El Remitente del Folio:'.$folioscrr->folio.' ha sido agregado');
        return redirect()->route('remitentes.index');
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
    public function edit(Request $request, RemitentesFolio $folioscrr)
    {   
        $remitente = $folioscrr;
        $folioscrr = FolioCrr::where('id', $remitente->folio_id)->first();
        $folioremitente = $remitente;
        $auditoria = Auditoria::find(getSession('auditoria_id'));

        return view('folios.remitentes.form', compact('auditoria','remitente','folioscrr','folioremitente'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RemitentesFolio $folioscrr)
    {
        $remitente = $folioscrr;
        $folioscrr = FolioCrr::where('id', $request->folio_id)->first();
        //dd($folioscrr);
        $request['usuario_modificacion_id'] = auth()->id();
        $remitente->update($request->all());

        setMessage('El Remitente del Folio: '.$folioscrr->folio.' ha sido actualizado');

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

    public function remitentecrear(FolioCrr $folioscrr)
    {
        //dd($folioscrr);
        $folioremitente = new RemitentesFolio();
        $auditoria = Auditoria::find(getSession('auditoria_id'));

        return view('folios.remitentes.form', compact('auditoria','folioscrr', 'folioremitente'));
    }

    private function setQuery($request)
    {
        $query = $this->model;
        $query = $query->where('auditoria_id',getSession('auditoria_id'));
        $query = $query->orderBy('id','DESC');

        return $query;
    }
}
