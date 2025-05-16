<?php

namespace App\Http\Controllers;

use App\Models\RemitentesFolio;
use Illuminate\Http\Request;
use App\Models\FolioCrr;
use App\Models\Auditoria;

class FolioCRRController extends Controller
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

        return view('folios.index', compact('auditoria','folios','request'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $folio = new FolioCrr();
        $auditoria = Auditoria::find(getSession('auditoria_id'));

        return view('folios.form', compact('auditoria','folio'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, )
    {
        $auditoria = Auditoria::find(getSession('auditoria_id'));
        $request['auditoria_id'] =getSession('auditoria_id');
        $request['usuario_creacion_id'] = auth()->id();
        mover_archivos($request, ['oficio_contestacion_general']);
        $folio  = FolioCrr::create($request->all());
        $folioscrr = $folio;
        $remitentes = RemitentesFolio::where('folio_id',$folioscrr->id)->get();
        
        setMessage('El folio ha sido agregado');
        //return redirect()->route('folioscrr.index');
        return view('remitentes.show', compact('folioscrr', 'auditoria','remitentes'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(FolioCrr $folioscrr)
    {
        //dd($folioscrr->id);

        $remitentes = RemitentesFolio::where('folio_id',$folioscrr->id)->get();

        $auditoria = Auditoria::find(getSession('auditoria_id'));

        // Aquí podrías mandar más datos si necesitas, además del folio
        return view('remitentes.show', compact('folioscrr', 'auditoria','remitentes'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(FolioCrr $folioscrr)
    {
        
		$folio = $folioscrr;
        $auditoria = Auditoria::find(getSession('auditoria_id'));
        

        return view('folios.form', compact('auditoria','folio'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FolioCrr $folioscrr)
    {
        $auditoria = Auditoria::find(getSession('auditoria_id'));
        $remitentes = RemitentesFolio::where('folio_id',$folioscrr->id)->get();
        $request['usuario_modificacion_id'] = auth()->id();
        mover_archivos($request, ['oficio_contestacion_general'],$folioscrr);
        $folioscrr->update($request->all());

        setMessage('El folio ha sido actualizado');
        //return redirect()->route('folioscrr.index');
        return redirect()->route('remitentes.show', compact('folioscrr', 'auditoria','remitentes'));
		
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

    public function remitentes(FolioCrr $folio){
        $auditoria = Auditoria::find(getSession('auditoria_id'));

        return view('remitentes.form', compact('auditoria', 'folio'));        
    }

    public function remitentesSave(Request $request, FolioCrr $folio){
        //dd($request);
        $auditoria = Auditoria::find(getSession('auditoria_id'));
        $request['usuario_creacion_id'] = auth()->id();

        RemitentesFolio::create($request->all());

        setMessage('El Remitente ha sido agregado');

        //return redirect()->route('folios.remitentesconsultar', $folio);
        //return redirect()->route('folios.remitentesconsultar', ['folio' => $folio->id]);
        //return view('remitentes.index', compact('auditoria'));        
        return redirect()->route('folioscrr.index');
    }




    public function remitentesconsultar(FolioCrr $folio)
    {
        $auditoria = Auditoria::find(getSession('auditoria_id'));
        $remitentes = $folio->remitentes;

        return view('remitentes.index', compact('auditoria', 'folio', 'remitentes'));      

    }

    private function setQuery($request)
    {
        $query = $this->model;
        $query = $query->where('auditoria_id',getSession('auditoria_id'));
        $query = $query->orderBy('id','DESC');

        return $query;
    }
}
