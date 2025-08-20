<?php

namespace App\Http\Controllers\AcuerdoConclusion;

use App\Http\Controllers\Controller;
use App\Http\Requests\AprobarFlujoAutorizacionRequest;
use App\Models\AcuerdoConclusion;
use App\Models\Auditoria;
use App\Models\Movimientos;
use App\Models\User;
use Illuminate\Http\Request;

class AcuerdoConclusionValidacionController extends Controller
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
        $auditoria=$auditoria->auditoria;
       
        return view('acuerdoconclusionvalidacion.form', compact('acuerdoconclusion','auditoria'));
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
        $mensaje = "";
        $url = route('acuerdoconclusion.index');
        $this->normalizarDatos($request);
        $acuerdoconclusion=$auditoria;
        
        $auditoria = Auditoria::find($acuerdoconclusion->auditoria_id);
        if(getSession('cp')==2022){
            $jefe=User::where('unidad_administrativa_id', substr($auditoria->userCreacion->unidad_administrativa_id, 0, 5).'0')->first();
            $lider=$auditoria->accion->lider;
        }else{
            $jefe = $auditoria->jefedepartamentoencargado;
            $lider = $auditoria->lidercp; 
        }

       if ($request->estatus == 'Aprobado') {
           $nivel_autorizacion = substr(auth()->user()->unidad_administrativa_id, 0, 3);
       } else {
           $nivel_autorizacion = substr(auth()->user()->unidad_administrativa_id, 0, 4);
       }

       $acuerdoconclusion->update(['fase_autorizacion' => $request->estatus == 'Aprobado' ? 'En autorización' : 'Rechazado', 'nivel_autorizacion' => $nivel_autorizacion]);
       setMessage($request->estatus == 'Aprobado' ?
           'La aprobación ha sido registrada y se ha enviado a autorización del superior.' :
           'El rechazo ha sido registrado.'
       );
       if ($request->estatus == 'Aprobado') {
        $titulo = 'Autorización del acuerdo de conclusión de '.$acuerdoconclusion->tipo;
        $mensaje = '<strong>Estimado(a) '.auth()->user()->titular->name.', '.auth()->user()->titular->puesto.':</strong><br>'
                        .auth()->user()->name.', '.auth()->user()->puesto.
                        '; ha aprobado la validación del acuerdo de conclusión de la auditoría No. '.$acuerdoconclusion->auditoria->numero_auditoria.
                        ', por lo que se requiere realice la autorización oportuna de la misma.';
        auth()->user()->insertNotificacion($titulo, $mensaje, now(), auth()->user()->titular->unidad_administrativa_id, auth()->user()->titular->id,GenerarLlave( $acuerdoconclusion).'/'.$acuerdoconclusion->tipo.'/Aut',$url);
        }else {
            
            $titulo = 'Acuerdo de conclusión de '.$acuerdoconclusion->tipo.' Rechazado';
            $mensaje = '<strong>Estimado(a) '.$acuerdoconclusion->usuarioCreacion->name.', '.$acuerdoconclusion->usuarioCreacion->puesto.':</strong><br>'
                            .'Ha sido rechazado el acuerdo de conclusión de la auditoría No. '.$acuerdoconclusion->auditoria->numero_auditoria.
                            ', por lo que se debe atender los comentarios y enviar la información corregida nuevamente.';
            
            auth()->user()->insertNotificacion($titulo, $mensaje, now(), $lider->unidad_administrativa_id, $lider->id, GenerarLlave($acuerdoconclusion).'/'.$acuerdoconclusion->tipo.'/Rechazo',$url);
            auth()->user()->insertNotificacion($titulo, $this->mensajeRechazo($jefe->name,$jefe->puesto,$acuerdoconclusion->auditoria->numero_auditoria), now(), $jefe->unidad_administrativa_id, $jefe->id, GenerarLlave($acuerdoconclusion).'/'.$acuerdoconclusion->tipo.'/Rechazo',$url);
        }
        Movimientos::create([
           'tipo_movimiento' => 'Validación del acuerdo de conclusión',
           'accion' => 'AcuerdoConclusion',
           'accion_id' => $acuerdoconclusion->id,
           'estatus' => $request->estatus,
           'usuario_creacion_id' => auth()->id(),
           'usuario_asignado_id' => auth()->id(),
           'motivo_rechazo' => $request->motivo_rechazo,
       ]);
        $notificacion=auth()->user()->notificaciones()->where('llave',GenerarLlave( $acuerdoconclusion).'/'.$acuerdoconclusion->tipo.'/ValD')->first();
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
                    .'Ha sido rechazado el registro del Acuerdo de Conclusión de las auditoría No. '.$numeroauditoria.', por lo que se debe atender y enviar la información corregida nuevamente.';;       

        return $mensaje;
    }
}
