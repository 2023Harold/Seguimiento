<?php

namespace App\Http\Controllers;

use App\Http\Requests\InformePrimeraEtapaRequest;
use App\Models\Auditoria;
use App\Models\AuditoriaAccion;
use App\Models\Constancia;
use App\Models\InformePrimeraEtapa;
use Illuminate\Http\Request;

class InformePrimeraEtapaController extends Controller
{
    protected $model;

    public function __construct(AuditoriaAccion $model)
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
        $acciones =  $this->setQuery($request)->orderBy('id')->paginate(30);

        return view('informeprimeraetapa.index', compact('request','acciones', 'auditoria'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $auditoria = Auditoria::find(getSession('auditoria_id'));               
        // $consecutivo=InformePrimeraEtapa::where('segauditoria_id',$auditoria->id)->whereNull('eliminado')->get()->count()+1;
        $informe = new InformePrimeraEtapa();
       
        return view('informeprimeraetapa.form', compact('auditoria','informe'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
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
    public function edit(Auditoria $auditoria)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    { 
       
        

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

    public function setQuery(Request $request)
    {
         $query = $this->model;

         $query = $query->where('segauditoria_id',getSession('auditoria_id'));

         
        if ($request->filled('consecutivo')) {
            $query = $query->where('consecutivo',$request->consecutivo);
         }

        if ($request->filled('tipo')) {
            $query = $query->where('tipo',$request->tipo);
        }
        if ($request->filled('monto_aclarar')) {
            $query = $query->where('monto_aclarar',$request->monto_aclarar);
        }
        return $query;
    }

    public function export(){
        
        $auditoria=Auditoria::find(getSession('auditoria_id'));     
        $directoruser = $auditoria->directorasignado;
        $direccionseguimiento = $directoruser->unidadAdministrativa->descripcion;

        $jefeuser = $auditoria->jefedepartamentoencargado;
        $jefeseguimiento = $jefeuser->unidadAdministrativa->descripcion;

        $ordenauditoria='NUMMORDN';
        $numeroauditoria=$auditoria->numero_auditoria;

        $numeroexpediente=$auditoria->radicacion->numero_expediente;

        $tipoauditoria=$auditoria->tipo_auditoria->descripcion;

        $entidades=explode(' - ',$auditoria->entidad_fiscalizable);
        $txtentidad=null;
        if (count($entidades)>1) {
            if ($entidades[1]=='MUNICIPIOS') {
                $bar = ucwords($entidades[2]);       
                $bar = ucwords(strtolower($bar));

                $txtentidad='Municipio de '.$bar;
            }
         }

        $periodo=$auditoria->periodo_revision;

        $director=$directoruser->name;
        $directorcargo=$directoruser->puesto;

        $jefe=$jefeuser->name;
        $jefecargo=$jefeuser->puesto;

        $rectext1='';
        $rectext2='';

        if (count($auditoria->accionesrecomendaciones)>0) {   
            $rectext1='y del Proceso de Atención a las Recomendaciones correspondientes'; 
            $rectext2='y; se precisaran las mejoras realizadas y las acciones emprendidas en relación con las recomendaciones que le fueron formuladas, o en su caso, justificara su improcedencia';          
        }
        $potext1="del Pliego de Observaciones";

        if(count($auditoria->totalpliegos)>0){
            if(count($auditoria->totalpliegos)==1){
                $potext1="del Pliego de";
            }else{
                $potext1="de los Pliegos de";
            }
        }

        $replacements=array();
        $replacementsrec=array();
        foreach ($auditoria->acciones as $accion) {
            $descripcion='';
            $analisis='';
            /**Solicitud de aclaracion */
            if($accion->segtipo_accion_id==1){
                $analisis=$accion->solicitudesaclaracion->analisis;
                $descripcion=$accion->solicitudesaclaracion->listado_documentos;
                
            }
            /**Recomendación */
            if($accion->segtipo_accion_id==2){
                $analisis=$accion->recomendaciones->analisis;
                $descripcion=$accion->recomendaciones->listado_documentos;

                $recaccionArr=[
                    'claveaccion'=>$accion->numero,
                    'tipoaccion'=>$accion->tipo,
                    'estado'=>'analisis',
                    'observacion'=>$accion->accion,
                    'descripcion'=>$descripcion,
                    'analisis'=>$analisis,
                    'normatividad'=>$accion->normativa_infringida,
                ];

                //$replacements=[$accionArr];
                array_push($replacementsrec, $recaccionArr);               
            }
            /**Pliego de observación */
            if($accion->segtipo_accion_id==3){
                $analisis=$accion->pliegosobservacion->analisis;
                $descripcion=$accion->pliegosobservacion->listado_documentos;
            }
            /**Promoción de responsabilidad administrativa sancionatoria */
            if($accion->segtipo_accion_id!=4){

                $accionArr=[
                    'claveaccion'=>$accion->numero,
                    'tipoaccion'=>$accion->tipo,
                    'estado'=>'analisis',
                    'observacion'=>$accion->accion,
                    'descripcion'=>$descripcion,
                    'analisis'=>$analisis,
                    'normatividad'=>$accion->normativa_infringida,
                ];

                //$replacements=[$accionArr];
                array_push($replacements, $accionArr);
            }

        }



        $template=new TemplateProcessorMod('bases-word/IS.docx');

        $template->setValue('direccionseguimiento',$direccionseguimiento);
        $template->setValue('departamentoseguimiento',$jefeseguimiento);
        $template->setValue('entidad',$txtentidad);
        $template->setValue('tipoauditoria',$tipoauditoria);
        $template->setValue('periodo',$periodo);
        $template->setValue('ordenauditoria',$ordenauditoria);
        $template->setValue('rectext1',$rectext1);
        $template->setValue('rectext2',$rectext2);
        $template->setValue('potext1',$potext1);
        if(!str_contains($auditoria->tipo_auditoria->descripcion, 'Legalidad')){
            $template->ddeleteBlock('bloquelegporec');
        }else{
            if(count($auditoria->totalpliegos) < 1 || count($auditoria->totalrecomendacion) < 1 ){           
                $template->ddeleteBlock('bloquelegporec');
            }else{
                $template->dcloneBlock('bloquelegporec', 1, true, true);
            }
        }
        $template->ddeleteBlock('bloquelegporecsi');       
        $template->dcloneBlock('bloquepo', 0, true, false, $replacements);
        $template->dcloneBlock('bloquerec', 0, true, false, $replacementsrec);





        $nombreword='IS';

        $template->saveAs($nombreword.'.docx');

        return response()->download($nombreword.'.docx')->deleteFileAfterSend(true);
    }
}
