<?php

namespace App\Http\Controllers\AcuerdoConclusion;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\AprobarFlujoAutorizacionRequest;
use App\Models\AcuerdoConclusion;
use App\Models\Movimientos;
use Illuminate\Http\Request;
use App\Models\Auditoria;
use App\Models\AuditoriaAccion;

class AcuerdoConclusionRevisionController extends Controller
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
        $acuerdoconclusion=$auditoria;
        $auditoria = Auditoria::find($acuerdoconclusion->auditoria_id);
    

        return view('acuerdoconclusionrevision.form', compact('acuerdoconclusion','auditoria'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AprobarFlujoAutorizacionRequest $request, AcuerdoConclusion $auditoria)
    {
        $this->normalizarDatos($request);
		$Atus=User::where('siglas_rol','ATUS')->first();
        //$director=User::where('unidad_administrativa_id',substr($recomendacion->userCreacion->unidad_administrativa_id, 0, 4).'00')->where('siglas_rol','DS')->first();
		
        $acuerdoconclusion=$auditoria;
       if ($request->estatus == 'Aprobado') {
           $nivel_autorizacion = substr(auth()->user()->unidad_administrativa_id, 0, 3);
       } else {
           $nivel_autorizacion = substr(auth()->user()->unidad_administrativa_id, 0, 4);
       }
       $url = route('acuerdoconclusion.index');
       //$acuerdoconclusion->update(['fase_autorizacion' =>  'En validación']);
       $acuerdoconclusion->update(['fase_autorizacion' => $request->estatus == 'Aprobado' ? 'En validación' : 'Rechazado', 'nivel_autorizacion' => $nivel_autorizacion]);
       setMessage($request->estatus == 'Aprobado' ?
           'La aprobación ha sido registrada y se ha enviado a validación del superior.' :
           'El rechazo ha sido registrado.'
       );

        $auditoria = Auditoria::find($acuerdoconclusion->auditoria_id);
        if(getSession('cp')==2022){
            $jefe=User::where('unidad_administrativa_id', substr($auditoria->userCreacion->unidad_administrativa_id, 0, 5).'0')->first();
            $lider=$auditoria->accion->lider;
            $analista=$auditoria->accion->analista;
        }else{
            $jefe = $auditoria->jefedepartamentoencargado;
            $analista = $auditoria->analistacp;
            $lider = $auditoria->lidercp; 
        }
        if ($request->estatus == 'Aprobado') {
        $titulo = 'Validación del acuerdo de conclusión de '.$acuerdoconclusion->tipo;
        $mensaje = '<strong>Estimado(a) '.auth()->user()->director->name.', '.auth()->user()->director->puesto.':</strong><br>'
                        .auth()->user()->name.', '.auth()->user()->puesto.
                        '; ha aprobado la revisión del acuerdo de conclusión de '.$acuerdoconclusion->tipo.' de la auditoría No. '.$auditoria->numero_auditoria.
                        ', por lo que se requiere realice la validación oportuna de la misma.';
        auth()->user()->insertNotificacion($titulo, $mensaje, now(), auth()->user()->director->unidad_administrativa_id, auth()->user()->director->id, GenerarLlave( $acuerdoconclusion).'/'.$acuerdoconclusion->tipo.'/ValD',$url);
		auth()->user()->insertNotificacion($titulo, $this->mensajeNotificacion($Atus->name,$Atus->puesto,$auditoria), now(), $Atus->unidad_administrativa_id, $Atus->id,GenerarLlave( $acuerdoconclusion).'/'.$acuerdoconclusion->tipo.'/Consulta',$url ); 
        }else {
            
            $titulo = 'Acuerdo de conclusión de '.$acuerdoconclusion->tipo.' Rechazado';
            $mensaje = '<strong>Estimado(a) '.$lider->name.', '.$lider->puesto.':</strong><br>'
                            .'Ha sido rechazado el acuerdo de conclusión de '.$acuerdoconclusion->tipo.' de la auditoría No. '.$auditoria->numero_auditoria.
                            ', por lo que se debe atender los comentarios y enviar la información corregida nuevamente a validación.';
            
            auth()->user()->insertNotificacion($titulo, $mensaje, now(), $lider->unidad_administrativa_id, $lider->id,GenerarLlave( $acuerdoconclusion).'/'.$acuerdoconclusion->tipo.'/Rechazo',$url);

        }
        Movimientos::create( [
           'tipo_movimiento' => 'Revisión del acuerdo de conclusión',
           'accion' => 'AcuerdoConclusion',
           'accion_id' => $acuerdoconclusion->id,
           'estatus' => $request->estatus,
           'usuario_creacion_id' => auth()->id(),
           'usuario_asignado_id' => auth()->id(),
           'motivo_rechazo' => $request->motivo_rechazo,
       ]);
        $notificacion=auth()->user()->notificaciones()->where('llave',GenerarLlave( $acuerdoconclusion).'/'.$acuerdoconclusion->tipo.'/RevJD')->first();
        
        $LeerNotificacion = auth()->user()->NotMarcarLeido($notificacion);
        $NotificacionRechazo=auth()->user()->notificaciones()->where('llave',GenerarLlave( $acuerdoconclusion).'/'.$acuerdoconclusion->tipo.'/Rechazo')->first();
        $LeerNotificacionRechazo = auth()->user()->NotMarcarLeido($NotificacionRechazo);

        return redirect()->route('acuerdoconclusion.index');
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

    private function normalizarDatos(Request $request)
    {
         if ($request->estatus == 'Aprobado') {
             $request['motivo_rechazo'] = null;
         }

         return $request;
    }
    private function mensajeRechazo(String $nombre, String $puesto, String $numeroauditoria)
    {
        $mensaje = '<strong>Estimado(a) '.$nombre.', '.$puesto.':</strong><br>'
                    .'Ha sido rechazado el registro del Acuerdo de Conclusión de las auditoría No. '.$numeroauditoria.'.';       

        return $mensaje;
    }
	
	private function mensajeNotificacion(String $nombre, String $puesto,$auditoria)
    {
        $mensaje = '<strong>Estimado(a) '.$nombre.', '.$puesto.':</strong><br>'
                    .'ha aprobado la revisión del acuerdo de conclusión de la auditoría No. '.$auditoria->numero_auditoria.', por lo que se debe revisar.';    

        return $mensaje;
    }
}
