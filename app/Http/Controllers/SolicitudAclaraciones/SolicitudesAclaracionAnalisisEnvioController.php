<?php

namespace App\Http\Controllers\SolicitudAclaraciones;

use App\Http\Controllers\Controller;
use App\Models\Auditoria;
use App\Models\AuditoriaUsuarios;
use App\Models\Movimientos;
use App\Models\Notificacion;
use App\Models\Recomendaciones;
use App\Models\SolicitudesAclaracion;
use App\Models\SolicitudesAclaracionContestacion;
use DB;
use Illuminate\Http\Request;

class SolicitudesAclaracionAnalisisEnvioController extends Controller
{
    public function edit(SolicitudesAclaracion $solicitud)
    {
        $request=new Request();
        if(empty($solicitud->listado_documentos)){
            setMessage('No se ha capturado información en el apartado de listado de documentos.','error');

            return back()->withInput();
        }
        /*$contestaciones = SolicitudesAclaracionContestacion::where('solicitudaclaracion_id',$solicitud->id)->get();
        if($contestaciones->count()==0){
            setMessage('No se ha capturado información en el apartado de contestaciones.','error');

            return back()->withInput();
        }*/
        $request['concluido']='Si';

        $solicitud->update($request->all());

        Movimientos::create([
            'tipo_movimiento' => 'Registro de la atención de la solicitud de aclaración.',
            'accion' => 'Solicitud de Aclaración',
            'accion_id' => $solicitud->id,
            'estatus' => 'Aprobado',
            'usuario_creacion_id' => auth()->id(),
            'usuario_asignado_id' => auth()->id(),
        ]);

        if (strlen($solicitud->nivel_autorizacion) == 3) {
            $nivel_autorizacion = $solicitud->nivel_autorizacion;
        } else {
            $nivel_autorizacion = substr(auth()->user()->unidad_administrativa_id, 0, 4);
        }
		$auditoria = Auditoria::find($solicitud->auditoria_id); 
        $cuenta_publicaSession = getSession('cp');
        $usaEquipo = usaEquipoTrabajo(); // guardamos en variable para reutilizar

        if ($usaEquipo) {
            $notificacion=auth()->user()->todasNotificacionesNuevas()->where('estatus', 'Pendiente')->where('llave', GenerarLlave($solicitud).'/Rechazo')->first();
            $registroLider = AuditoriaUsuarios::where('auditoria_id', $auditoria->id)->where('rol_code', 'Lider')->where('estatus', 'Activo')->first();
            $equipoId = $registroLider->equipo_id ?? null;
            $liderIndividual = null; // ya no se usa para notificar
        } else {
            $notificacion=auth()->user()->notificaciones()->where('llave',GenerarLlave($solicitud).'/Rechazo')->first();
            $lider_asignadoCP = ($cuenta_publicaSession != 2022) ? $auditoria->lidercp : $auditoria->lider;
        }
        auth()->user()->NotMarcarLeido($notificacion);

		$idUser = auth()->user()->id;   
		
        if(!empty($notificacion)&& ($notificacion->estatus == 'Pendiente')){
                $notificacion = Notificacion::find($notificacion->id);
                // Actualizar el estatus a 'Leído'
                $notificacion->update(['estatus' => 'Leído']);
            }
        $url = route('solicitudesaclaracionatencion.index');
        $solicitud->update(['fase_autorizacion' =>  'En revisión 01', 'nivel_autorizacion' => $nivel_autorizacion]);

        $titulo = 'Revisión del registro de la atención de la solicitud de acalaración de la acción No. '.$solicitud->accion->numero.' de la Auditoría No. '.$solicitud->accion->auditoria->numero_auditoria;
        $mensaje = '<strong>Estimado (a) ' . $solicitud->accion->lider->name . ', ' . $solicitud->accion->lider->puesto . ':</strong><br>
                    Ha sido registrada la atención de la solicitud de acalaración de la acción No. '.$solicitud->accion->numero.' de la Auditoría No. '.$solicitud->accion->auditoria->numero_auditoria . ', por parte del ' .
                    auth()->user()->puesto.' '.auth()->user()->name . ', por lo que se requiere realice la revisión.';
        if ($usaEquipo) {
            auth()->user()->insertNotificacion($titulo, $mensaje, now(),null,null, GenerarLlave($solicitud).'/RevL',$url, $auditoria->id, $equipoId,'Lider');
        }else{
            auth()->user()->insertNotificacion($titulo, $mensaje, now(), $solicitud->accion->lider->unidad_administrativa_id,$solicitud->accion->lider->id, GenerarLlave($solicitud).'/RevL', $url);
        }
        
        $staffA = AuditoriaUsuarios::select('segusers.id','segusers.name','segusers.puesto', 'segusers.unidad_administrativa_id', 'segusers.siglas_rol', 'segusers.estatus',   
                                            DB::raw("(case when(segusers.id = segauditoria_usuarios.staff_id) THEN segusers.name ELSE NULL END) AS staffAsignado01"),
                                            )->join('segusers', 'segusers.id', '=', 'segauditoria_usuarios.staff_id')->where('auditoria_id', $auditoria->id)->get()->toArray();
        foreach ($staffA as $staff) {
            if (!empty($staff['id'])) {
                $tituloStaff = 'Revisión del registro de atención de la solicitud de aclaración de la Acción No. '.$solicitud->accion->numero.' de la Auditoría No. '.$solicitud->accion->auditoria->numero_auditoria;

                $mensajeStaff = '<strong>Estimado(a) '.$staff['name'].', '.$staff['puesto'].':</strong><br>'
                                .auth()->user()->name.', '.auth()->user()->puesto.
                                '; ha aprobado el registro de atención de la solicitud de aclaración de la Acción No. '.$solicitud->accion->numero.' de la Auditoría No. '.$solicitud->accion->auditoria->numero_auditoria.
                                ', por lo que se le notifica para su conocimiento.';
                auth()->user()->insertNotificacion($tituloStaff, $mensajeStaff, now(), $staff['unidad_administrativa_id'], $staff['id'], GenerarLlave( $solicitud).'/Consulta', $url);
            }
        }
        setMessage('Se han enviado la información de la atención de la solicitud de acalaración a revisión');

        return redirect()->route('solicitudesaclaracionatencion.index');
    }

}
