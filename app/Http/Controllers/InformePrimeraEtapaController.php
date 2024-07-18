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
        $request['auditoria_id']= getSession('auditoria_id');
        //$informe  = InformePrimeraEtapa::create($request->all());

      
        $params = [
        
            'direccion'=>'unidad_admnistrativa',
            'departamento'=>'AAA',
            'mes'=>'Junio',
            'dia'=>'19',
            'anio'=>'2024',
            'orden_auditoria'=>$request->numero_ordenauditoria,
            'numero_auditoria'=>'OSFEM/X/XXX/202X',
            'numero_expediente'=>'OSFEM/X/XXX/202X',
            'oficio_numero'=>$request->numero_oficio_entro,
            'informe_auditoria'=>'XXXX/xxx/xxxxx',
            'orden_numero'=>'XXXX/xxx/xxxxx',
            'cargo'=>'XXXXXXX ',
            'domicilio'=>'DRFGNIDGIDGIRDGIRDFFDGLDK',
            'auditoria'=>'ÑLPOLOIKK',
            'practicada_a'=>'DFGHTYEJ ',
            'periodo_comprendido'=>' SDRGSDRG',
            'oficio_numero2'=>'XX/XXX/XXXX/XXXX',
            'constante_a'=>' DFARESF',
            'fojas_utiles'=>$request->fojas_utiles,
            'nombre_subsecretario'=>'NOMBRE_SUBSCRETARIO ',
            'lisv'=>' XXX/XXXX/XXX/XXXX',
            

        ];

        $constanciareporte = reporte(1, 'Fiscalizacion/Seguimiento/Pac/Jefe/ofis', $params, 'docx'); 
        
        // $constancia = new Constancia();
        // $constancia->constancia_pdf=$constanciareporte;

        //dd($constancia);
    
            return response()->download($constanciareporte);
            
            
           // return redirect()->route('constancia.mostrarConstancia', ['constancia'=>$constancia, 'rutaCerrar'=>'informeprimeraetapa.index']);
        // dd($request->all());
        // $this->actualizaProgresivo();
        // setMessage('El informe ha sido guardado');
        //return redirect() -> route('informeprimeraetapa.index');
    


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
}
