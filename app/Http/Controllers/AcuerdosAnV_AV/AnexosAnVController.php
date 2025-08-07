<?php

namespace App\Http\Controllers\AcuerdosAnV_AV;

use App\Http\Controllers\Controller;
use App\Models\AcuerdosValoracion;
use App\Models\AnexosAnV_AV;
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
use PhpParser\Node\Stmt\Else_;


class AnexosAnVController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public $validationRules;
    public $attributeNames;
    public $errorMessages;
    protected $model;
    public function index()
    {
        //dd("index");
        $acuerdoanvav = AcuerdosValoracion::find(getSession('anvav_id_session'));
        $folio = FolioCRR::find(getSession('folio_id_session'));  
        $remitentes = RemitentesFolio::where('folio_id',$folio->id)->get();
        //dd("anexos anv av show :D",$acuerdoanvav,$folio,$remitentes);
        $acuerdoaccion = "Consulta";
        $auditoria = Auditoria::find(getSession('auditoria_id'));

        $anexosacuerdoanvav = AnexosAnV_AV::where('anvav_id',$acuerdoanvav->id)->get();

        //dd($acuerdoanvav);
        return view('folios.acuerdosanvav_anexos.index', compact('auditoria','acuerdoanvav','folio','remitentes','anexosacuerdoanvav'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function create(Request $request)
    {  
        $acuerdoanvav = AcuerdosValoracion::find(getSession('anvav_id_session'));
        $folio = FolioCRR::find(getSession('folio_id_session'));  
        //dd("anexos anv av create :D",$acuerdoanvav);
        $anexoacuerdoaccion = "Agregar";
        $anexosacuerdoanvav = new AnexosAnV_AV();
        //DD($folio,$acuerdoaccion);
        $auditoria = Auditoria::find(getSession('auditoria_id'));
        $remitentes = RemitentesFolio::where('folio_id',$folio->id)->get();
        

        return view('folios.acuerdosanvav_anexos.form', compact('acuerdoanvav','auditoria','anexoacuerdoaccion','anexosacuerdoanvav','folio','remitentes'));
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
        $acuerdoanvav = AcuerdosValoracion::find(getSession('anvav_id_session'));
        $auditoria = Auditoria::find(getSession('auditoria_id'));
        $folio = FolioCRR::find(getSession('folio_id_session'));  
        $request['usuario_creacion_id'] = auth()->id();
        $remitentes = RemitentesFolio::where('folio_id',$folio->id)->get();
        $request['anvav_id'] = $acuerdoanvav->id;
        mover_archivos($request, ['archivo','of_notificacion'], null);
        if(count($acuerdoanvav->anexoanvav)==0){
            $request['consecutivo'] = 1;
        }else{
            $request['consecutivo'] = count($acuerdoanvav->anexoanvav) + 1;
        }
        //dd(count($acuerdoanvav->anexoanvav));
        $Anexoacuerdo  = AnexosAnV_AV::create($request->all());

        setMessage('Se registro el acuerdo correctamente');
        return redirect()->route('anexosanvav.index');
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $acuerdoanvav = AcuerdosValoracion::find(getSession('anvav_id_session'));
        $folio = FolioCRR::find(getSession('folio_id_session'));  
        $remitentes = RemitentesFolio::where('folio_id',$folio->id)->get();
        //dd("anexos anv av show :D",$acuerdoanvav,$folio,$remitentes);
        $acuerdoaccion = "Consulta";
        $auditoria = Auditoria::find(getSession('auditoria_id'));

        //dd($acuerdoanvav);
        return view('folios.acuerdosanvav.show', compact('auditoria','acuerdoanvav','folio','remitentes'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(FolioCrr $folio)
    {
        $acuerdoaccion = "Editar";
        $auditoria = Auditoria::find(getSession('auditoria_id'));
        
        return view('folios.foliosanexos.form', compact('auditoria','folio'));
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
    
    public function actualizaProgresivo()
    {
        $numeroSiguiente = 1;
        $modelName = $this->model;

        $er_records = $modelName::where('seganv_av_anexos', getSession('anvav_id_session'));

        $er_records = $er_records->orderBy('consecutivo')->get();

        foreach ($er_records as $er_record) {
            $er_record->update(['consecutivo' => $numeroSiguiente]);
            $numeroSiguiente++;
        }
    }

}
