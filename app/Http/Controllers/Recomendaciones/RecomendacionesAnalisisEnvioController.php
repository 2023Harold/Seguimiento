<?php

namespace App\Http\Controllers\Recomendaciones;

use App\Http\Controllers\Controller;
use App\Models\Auditoria;
use App\Models\AuditoriaUsuarios;
use App\Models\Movimientos;
use App\Models\Notificacion;
use App\Models\Recomendaciones;
use App\Models\RecomendacionesContestacion;
use DB;
use Illuminate\Http\Request;
use URL;

class RecomendacionesAnalisisEnvioController extends Controller
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
    public function edit(Recomendaciones $recomendacion)
    {
        $auditoria = Auditoria::find($recomendacion->auditoria_id); 
        $request=new Request();
        if(empty($recomendacion->listado_documentos)){
            setMessage('No se ha capturado información en el apartado de listado de documentos.','error');

            return back()->withInput();
        }
        /*$contestaciones = RecomendacionesContestacion::where('recomendacion_id',$recomendacion->id)->get();
        if($contestaciones->count()==0){
            setMessage('No se ha capturado información en el apartado de contestaciones.','error');

            return back()->withInput();
        }*/
        $request['concluido']='Si';
        $recomendacion->update($request->all());

        $mov = Movimientos::create([
            'tipo_movimiento' => 'Registro de la atención de la recomendación',
            'accion' => 'Recomendación',
            'accion_id' => $recomendacion->id,
            'estatus' => 'Aprobado',
            'usuario_creacion_id' => auth()->id(),
            'usuario_asignado_id' => auth()->id(),
        ]);

        if (strlen($recomendacion->nivel_autorizacion) == 3) {
            $nivel_autorizacion = $recomendacion->nivel_autorizacion;
        } else {
            $nivel_autorizacion = substr(auth()->user()->unidad_administrativa_id, 0, 4);
        }

		$notificacion=auth()->user()->notificaciones()->where('llave',GenerarLlave( $recomendacion).'/Rechazo')->first();
		$LeerNotificacion = auth()->user()->NotMarcarLeido($notificacion);
        $url = route('recomendacionesatencion.index');
        
        $recomendacion->update(['fase_autorizacion' =>  'En revisión 01', 'nivel_autorizacion' => $nivel_autorizacion]);
        $titulo = 'Revisión del registro de la atención de la recomendación de la acción No. '.$recomendacion->accion->numero.' de la Auditoría No. '.$recomendacion->accion->auditoria->numero_auditoria;
        $mensaje = '<strong>Estimado (a) ' . $recomendacion->accion->lider->name . ', ' . $recomendacion->accion->lider->puesto . ':</strong><br>
                    Ha sido registrada la atención de la recomendación de la acción No. '.$recomendacion->accion->numero.' de la Auditoría No. '.$recomendacion->accion->auditoria->numero_auditoria . ', por parte del ' .
                    auth()->user()->puesto.' '.auth()->user()->name . ', por lo que se requiere realice la revisión.';
        
        auth()->user()->insertNotificacion($titulo, $mensaje, now(), $recomendacion->accion->lider->unidad_administrativa_id,$recomendacion->accion->lider->id,  GenerarLlave( $recomendacion).'/RevL', $url);
        setMessage('Se han enviado la información de la recomendación a revisión');

        $staffA = AuditoriaUsuarios::select('segusers.id','segusers.name','segusers.puesto', 'segusers.unidad_administrativa_id', 'segusers.siglas_rol', 'segusers.estatus',   
                                            DB::raw("(case when(segusers.id = segauditoria_usuarios.staff_id) THEN segusers.name ELSE NULL END) AS staffAsignado01"),
                                            )->join('segusers', 'segusers.id', '=', 'segauditoria_usuarios.staff_id')->where('auditoria_id', $auditoria->id)->get()->toArray();
        foreach ($staffA as $staff) {
            if (!empty($staff['id'])) {
                $tituloStaff = 'Revisión del registro de atención de la recomendación de la Acción No. '.$recomendacion->accion->numero.' de la Auditoría No. '.$recomendacion->accion->auditoria->numero_auditoria;

                $mensajeStaff = '<strong>Estimado(a) '.$staff['name'].', '.$staff['puesto'].':</strong><br>'
                                .auth()->user()->name.', '.auth()->user()->puesto.
                                '; ha aprobado el registro de atención de la recomendación de la Acción No. '.$recomendacion->accion->numero.' de la Auditoría No. '.$recomendacion->accion->auditoria->numero_auditoria.
                                ', por lo que se le notifica para su conocimiento.';

                auth()->user()->insertNotificacion($tituloStaff, $mensajeStaff, now(), $staff['unidad_administrativa_id'], $staff['id'], GenerarLlave( $recomendacion).'/Consulta', $url);
            }
        }
        
        return redirect()->route('recomendacionesatencion.index');
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
}
