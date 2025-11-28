<?php

namespace App\Http\Controllers\AcuerdosAnV_AV;

use App\Http\Controllers\Controller;
use App\Models\AcuerdosValoracion;
use App\Models\Auditoria;
use App\Models\AuditoriaAccion;
use App\Models\FolioCRR;
use App\Models\ListadoEntidades;
use App\Models\RemitentesFolio;
use App\Models\TurnoOIC;
use App\Models\User;
use DB;
use FontLib\Table\Type\name;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\Style\Language;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\Style\Table;


class AnVController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(FolioCRR $folio)
    {
        dd($folio);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(FolioCRR $folio)
    {
        //dd($folio);
        //$folio = FolioCRR::find(getSession('folio_id_session'));  
        $acuerdoaccion = "Agregar";
        $acuerdoanvav = new AcuerdosValoracion();
        //DD($folio,$acuerdoaccion);
        $auditoria = Auditoria::find(getSession('auditoria_id'));
        $remitentes = RemitentesFolio::where('folio_id',$folio->id)->get();
        setSession('folio_id_session',$folio->id);
        $acuerdoanvav_tipo_of = "";
        //dd($folio->numero_oficio);

        return view('folios.acuerdosanvav.form', compact('auditoria','acuerdoaccion','acuerdoanvav','folio','acuerdoanvav_tipo_of','remitentes'));
    
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request);
        $auditoria = Auditoria::find(getSession('auditoria_id'));
        //$folio = FolioCrr::where('id', $request->folio_id)->first();
        $folio = FolioCRR::find(getSession('folio_id_session'));  
        $request['usuario_creacion_id'] = auth()->id();
        $remitentes = RemitentesFolio::where('folio_id',$folio->id)->get();
        
        $anvav  = AcuerdosValoracion::create($request->all());
        //$this->actualizaProgresivo();

        setMessage('Se registro el acuerdo correctamente');
        return redirect()->route('acuerdosanvav.show', $folio);
        
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(FolioCRR $folio)
    {
        //dd($folio->acuerdoanvav);
        $acuerdoaccion = "Consulta";
        //dd($request);
        $auditoria = Auditoria::find(getSession('auditoria_id'));
        $acuerdoanvav = AcuerdosValoracion::where('folio_id',$folio->id)->get()->first();
        $remitentes = RemitentesFolio::where('folio_id',$folio->id)->get();
        if($folio->acuerdoanvav == null){
            setSession('folio_id_session',$folio->id);
        }else{
            setSession('anvav_id_session',$acuerdoanvav->id);
            setSession('folio_id_session',$folio->id);
        }

        
        //dd($acuerdoanvav);
        return view('folios.acuerdosanvav.show', compact('auditoria','acuerdoanvav','folio','remitentes'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(AcuerdosValoracion $folio)
    {
        $acuerdoanvav = $folio;   
        
        $acuerdoanvav_tipo_of = $acuerdoanvav->tipo_doc;
        $folio = FolioCRR::find(getSession('folio_id_session'));  
        $acuerdoaccion = "Editar";
        //DD($folio,$acuerdoaccion);
        $auditoria = Auditoria::find(getSession('auditoria_id'));
        $remitentes = RemitentesFolio::where('folio_id',$folio->id)->get();
        setSession('folio_id_session',$folio->id);

        return view('folios.acuerdosanvav.form', compact('auditoria','acuerdoaccion','acuerdoanvav','folio','acuerdoanvav_tipo_of','remitentes'));
    
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AcuerdosValoracion $folio)
    {
        $acuerdoanvav = $folio;
        $folio = FolioCRR::find(getSession('folio_id_session'));  
        $request['usuario_modificacion_id'] = auth()->id();
        //dd($request); 
        $acuerdoanvav->update($request->all());
        setMessage('El acuerdo ha sido actualizado');

        return redirect()->route('acuerdosanvav.show', $folio);
        
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


    public function anvcrear(FolioCRR $folio)
    {
        //dd($folio);
        //$folio = FolioCRR::find(getSession('folio_id_session'));  
        $acuerdoaccion = "Agregar";
        $acuerdoanvav = new AcuerdosValoracion();
        //DD($folio,$acuerdoaccion);
        $auditoria = Auditoria::find(getSession('auditoria_id'));
        $remitentes = RemitentesFolio::where('folio_id',$folio->id)->get();
        setSession('folio_id_session',$folio->id);
        $acuerdoanvav_tipo_of = "";
        //dd($folio->numero_oficio);

        return view('folios.acuerdosanvav.form', compact('auditoria','acuerdoaccion','acuerdoanvav','folio','acuerdoanvav_tipo_of','remitentes'));
    
    }
}
