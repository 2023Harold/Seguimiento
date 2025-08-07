<?php

namespace App\Http\Controllers\Informe;

use App\Http\Controllers\Controller;
use App\Http\Requests\InformePrimeraEtapaRequest;
use App\Models\Auditoria;
use App\Models\AuditoriaAccion;
use App\Models\InformePrimeraEtapa;
use App\Models\PliegosObservacion;
use App\Models\Recomendaciones;
use App\Models\ListadoEntidades;
use App\Models\Segpras;
use App\Models\SolicitudesAclaracion;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Luecano\NumeroALetras\NumeroALetras;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\ToArray;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat\NumberFormatter;
use PhpOffice\PhpWord\TemplateProcessor;
use PhpParser\Node\Stmt\Else_;
use SebastianBergmann\Type\TrueType;
use Carbon\Carbon;



class InformePrimeraEtapaController extends Controller
{
    protected $model;

    public function __construct(InformePrimeraEtapa $model)
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
        $informeprimeraetapa=InformePrimeraEtapa::where('auditoria_id',getSession('auditoria_id'))->first();
        

        return view('informeprimeraetapa.index', compact('request','informeprimeraetapa', 'auditoria'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $auditoria = Auditoria::find(getSession('auditoria_id'));     
        $tipo='recomendaciones';          
        // $consecutivo=InformePrimeraEtapa::where('segauditoria_id',$auditoria->id)->whereNull('eliminado')->get()->count()+1;
        $informeprimeraetapa = new InformePrimeraEtapa();
        $request['usuario_creacion_id'] = auth()->user()->id;
       
        return view('informeprimeraetapa.form', compact('auditoria','informeprimeraetapa','tipo'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //mover_archivos($request, ['informe','acuse_envio','acuse_notificacion']);
        $request['usuario_creacion_id'] = auth()->user()->id;
        $request['auditoria_id']=getSession('auditoria_id');            
        InformePrimeraEtapa::create($request->all());
        setMessage("Los datos se han guardado correctamente.");

        return redirect() -> route('informeprimeraetapa.index');
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
    public function edit(InformePrimeraEtapa $auditoria)
    {
        // dd($auditoria);
      
        $informeprimeraetapa=$auditoria;       
        $tipo=$informeprimeraetapa->tipo;                 
        $auditoria = Auditoria::find(getSession('auditoria_id'));        
       
        return view('informeprimeraetapa.form', compact('auditoria','informeprimeraetapa','tipo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, InformePrimeraEtapa $auditoria)
    { 
        $informeprimeraetapa=$auditoria;
        mover_archivos($request, ['informe']);
        $request['usuario_modificacion_id'] = auth()->user()->id;        
        $request['auditoria_id']=getSession('auditoria_id');            
        $auditoria->update($request->all());
        $auditoria=$auditoria->auditoria;



        setMessage("Los datos se han actualizado correctamente.");
        return redirect() -> route('informeprimeraetapa.index',compact('auditoria','informeprimeraetapa'));

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

    
    public function informepliegos()
    {
        $auditoria = Auditoria::find(getSession('auditoria_id'));     
        $tipo='pliegos';          
        $informeprimeraetapa = new InformePrimeraEtapa();
        $request['usuario_creacion_id'] = auth()->user()->id;
      
       
        return view('informeprimeraetapa.form', compact('auditoria','informeprimeraetapa','tipo'));

    }
    private function normalizarDatos(Request $request)
    {
        if ($request->estatus == 'Aprobado') {
            $request['motivo_rechazo'] = null;
        }

        return $request;
    }
    public function auditoria(Auditoria $auditoria)
    {
        setSession('informeprimeraetapa_auditoria_id',$auditoria->id);

        return redirect()->route('informeprimeraetapa.create');
    }
    
}
