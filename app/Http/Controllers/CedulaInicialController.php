<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use App\Models\SUTIC\EntidadFiscalizableIntra;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CedulaInicialController extends Controller
{
    protected $model;
    
    public function __construct(Auditoria $model)
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
        $auditorias = $this->setQuery($request)->orderBy('id')->paginate(30);
        
               
        return view('cedulainicial.index', compact('auditorias', 'request'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        $entidadregistrada = EntidadFiscalizableIntra::where('PkCveEntFis',$auditoria->id)->first();
        $entidad = $entidadregistrada->NomEntFis;
        $tipo_entidad = $entidadregistrada->Ambito;

        $totalrecomendaciones= (empty($auditoria->totalrecomendacion)?0:$auditoria->totalrecomendacion->count());
        $totalpras = (empty($auditoria->totalpras)?0:$auditoria->totalpras->count());
        $totalsolacl = (empty($auditoria->totalsolacl)?0:$auditoria->totalsolacl->count());
        $totalpliegos = (empty($auditoria->totalpliegos)?0:$auditoria->totalpliegos->count());

       $params = [
            'entidad' => $entidad,
            'tipoentidad' => $tipo_entidad,
            'total_rec' => $totalrecomendaciones,
            'total_pra' => $totalpras,
            'total_sol' => $totalsolacl,
            'total_plie' => $totalpliegos,
        ];

        $datosConstancia = [
            'nombreConstancia' => 'Fiscalizacion/Seguimiento/CedulaInicial',
            'parametros' => $params,
            'where' => base64_encode(Str::random(5).$auditoria->id.Str::random(5)),
        ];

        $params['where'] = $auditoria->id;

        $preconstancia = reporte($auditoria->id, 'Fiscalizacion/Seguimiento/CedulaInicial', $params, 'pdf');
        $archivorutaxml = reporte($auditoria->id, 'Fiscalizacion/Seguimiento/CedulaInicial', $params, 'xml');
        $b64archivoxml = chunk_split(base64_encode(file_get_contents(base_path().'/public/'.$archivorutaxml)));       

        return view('cedulainicial.form', compact('auditoria', 'preconstancia', 'b64archivoxml', 'datosConstancia', 'archivorutaxml'));
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
        //
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
         
        if(in_array("Titular Unidad de Seguimiento", auth()->user()->getRoleNames()->toArray())){     
            $unidadAdministrativa=auth()->user()->unidad_administrativa_id;
            $query = $query->where('fase_autorizacion','Autorizado');
        } 

        if(in_array("Director de Seguimiento", auth()->user()->getRoleNames()->toArray())){     
            $unidadAdministrativa=auth()->user()->unidad_administrativa_id;
            $query = $query->where('direccion_asignada_id',$unidadAdministrativa);
        }
        
        $query = $query->whereHas('acciones', function($q){
            if(in_array("Jefe de Departamento de Seguimiento", auth()->user()->getRoleNames()->toArray())){     
                $unidadAdministrativa=auth()->user()->unidad_administrativa_id;
                $q = $q->where('departamento_asignado_id',$unidadAdministrativa)->orWhere('departamento_encargado_id',$unidadAdministrativa);
            } 
            if(in_array("Lider de Proyecto", auth()->user()->getRoleNames()->toArray())){    
                $userLider=auth()->user(); 
                $q = $q->where('lider_asignado_id',$userLider->id);
            }
            if(in_array("Analista", auth()->user()->getRoleNames()->toArray())){           
                $userAnalista=auth()->user(); 
                $q = $q->where('analista_asignado_id',$userAnalista->id);
            }            
            if(in_array("Administrador del Sistema", auth()->user()->getRoleNames()->toArray())||
               in_array("Auditor Superior", auth()->user()->getRoleNames()->toArray())||
               in_array("Secretaria Técnica", auth()->user()->getRoleNames()->toArray())){                 
                $q = $q->whereNotNull('analista_asignado_id');
            }
        });        

        
        
                
        if ($request->filled('numero_auditoria')) {
             $numeroAuditoria=strtolower($request->numero_auditoria);
             $query = $query->whereRaw('LOWER(numero_auditoria) LIKE (?) ',["%{$numeroAuditoria}%"]);
         }

        if ($request->filled('entidad_fiscalizable')) {
            $entidadFiscalizable=strtolower($request->entidad_fiscalizable);
            $query = $query->whereRaw('LOWER(entidad_fiscalizable) LIKE (?) ',["%{$entidadFiscalizable}%"]);
        }

        if ($request->filled('acto_fiscalizacion')) {
            $actoFiscalizacion=strtolower($request->acto_fiscalizacion);
            $query = $query->whereRaw('LOWER(acto_fiscalizacion) LIKE (?) ',["%{$actoFiscalizacion}%"]);
        }

        return $query;
    }
}
