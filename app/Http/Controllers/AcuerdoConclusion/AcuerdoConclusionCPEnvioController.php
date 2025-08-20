<?php

namespace App\Http\Controllers\AcuerdoConclusion;

use App\Http\Controllers\Controller;
use App\Models\AcuerdoConclusion;
use App\Models\Auditoria;
use App\Models\Movimientos;
use App\Models\User;
use Illuminate\Http\Request;

class AcuerdoConclusionCPEnvioController extends Controller
{
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
    public function edit(AcuerdoConclusion $auditoria)
    {
        
        $url = route('acuerdoconclusion.index');   
        $acuerdoconclusion=$auditoria;    

        $auditoria = Auditoria::find($acuerdoconclusion->auditoria_id);
        if(getSession('cp')==2022){
            $jefe=User::where('unidad_administrativa_id', substr($auditoria->userCreacion->unidad_administrativa_id, 0, 5).'0')->first();
            $lider=$auditoria->accion->lider;
        }else{
            $jefe = $auditoria->jefedepartamentoencargado;
            $lider = $auditoria->lidercp; 
        }
        
        $acuerdoconclusion->update(['fase_autorizacion' =>  'En revisión']);
        $NotificacionRechazo=auth()->user()->notificaciones()->where('llave',GenerarLlave( $acuerdoconclusion).'/'.$acuerdoconclusion->tipo.'/Rechazo')->first();
        $LeerNotificacionRechazo = auth()->user()->NotMarcarLeido($NotificacionRechazo);

        $titulo = 'Revisión del Acuerdo de Conclusión de '.$acuerdoconclusion->tipo;
        $mensaje = '<strong>Estimado (a) ' . auth()->user()->jefe->name . ', ' . auth()->user()->jefe->puesto . ':</strong><br>
                    Ha sido registrado el acuerdo de conclusión de '.$acuerdoconclusion->tipo.' de la auditoría No. ' . $acuerdoconclusion->auditoria->numero_auditoria . ', por parte del ' .
                    auth()->user()->puesto.' '.auth()->user()->name . ', por lo que se requiere realice la revisión.';

        auth()->user()->insertNotificacion($titulo, $mensaje, now(), $jefe->unidad_administrativa_id,$jefe->id,GenerarLlave( $acuerdoconclusion).'/'.$acuerdoconclusion->tipo.'/RevJD',$url);
        setMessage('Se ha enviado el acuerdo de conclusión a revisión');
    
        Movimientos::create([
            'tipo_movimiento' => 'Registro del acuerdo de conclusión',
                'accion' => 'AcuerdoConclusion',
                'accion_id' => $acuerdoconclusion->id,
                'estatus' => 'Aprobado',
                'usuario_creacion_id' => auth()->id(),
                'usuario_asignado_id' => auth()->id(),
                'usuario_modificacion_id' => auth()->id(),
        ]);

        return redirect()->route('acuerdoconclusion.index');


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
        $query = new Auditoria();
        $query = $query->whereNotNull('fase_autorizacion')
           ->where('fase_autorizacion','Autorizado');

       if(in_array("Administrador del Sistema", auth()->user()->getRoleNames()->toArray())||
          in_array("Auditor Superior", auth()->user()->getRoleNames()->toArray())||
          in_array("Titular Unidad de Seguimiento", auth()->user()->getRoleNames()->toArray())){



       }elseif(in_array("Director de Seguimiento", auth()->user()->getRoleNames()->toArray())){

           $query = $query->whereNotNull('fase_autorizacion')
                       ->where('fase_autorizacion','Autorizado')
                       ->whereNotNull('direccion_asignada_id')
                       ->where('direccion_asignada_id',auth()->user()->unidad_administrativa_id);
       }elseif(in_array("Jefe de Departamento de Seguimiento", auth()->user()->getRoleNames()->toArray())){
           $query = $query->whereNotNull('departamento_encargado_id')
                       ->where('departamento_encargado_id',auth()->user()->unidad_administrativa_id);
       }

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
