<?php

namespace App\Http\Controllers;

use App\Models\AuditoriaAccion;
use App\Models\PliegosContestacion;
use App\Models\PliegosDocumento;
use App\Models\PliegosObservacion;
use Illuminate\Http\Request;

class PliegosObservacionAtencionCalificacionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( )
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
    public function show(PliegosObservacion $pliegosObservacion)
    {
        $accion=AuditoriaAccion::find(getSession('pliegosobservacionauditoriaaccion_id'));
        $auditoria=$accion->auditoria;

        return view('pliegosobservacioncalificacion.show',compact('pliegosobservacion','accion','auditoria'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(PliegosObservacion $pliegosobservacion)
    {
        $accion=AuditoriaAccion::find(getSession('pliegosobservacionauditoriaaccion_id'));
        $auditoria=$accion->auditoria;

        return view('pliegosobservacionatencioncalificacion.form',compact('pliegosobservacion','accion','auditoria'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PliegosContestacion $pliegoscontestacion)
    {
        $director=auth()->user()->director;
        $documentos = PliegosDocumento::where('pliegosobservacion_id',$pliegosobservacion->id)->get();
        if($documentos->count()==0){
            setMessage('No se ha capturado información en el apartado de listado de documentos.','error');

            return back()->withInput();
        }
        $contestaciones = PliegosContestacion::where('pliegosobservacion_id',$recomendacion->id)->get();
        if($contestaciones->count()==0){
            setMessage('No se ha capturado información en el apartado de contestaciones.','error');

            return back()->withInput();
        }
        $request['concluido']='Si';
        $recomendacion->update($request->all());

         Movimientos::create([
            'tipo_movimiento' => 'Registro de la calificación y la conclusión de la atención de la recomendación',
            'accion' => 'Pliegos observacion',
            'accion_id' => $pliegosobservacion->id,
            'estatus' => 'Aprobado',
            'usuario_creacion_id' => auth()->id(),
            'usuario_asignado_id' => auth()->id(),
        ]);

        if (strlen($pliegosobservacion->nivel_autorizacion) == 3) {
            $nivel_autorizacion = $pliegosobservacion->nivel_autorizacion;
        } else {
            $nivel_autorizacion = substr(auth()->user()->unidad_administrativa_id, 0, 4);
        }

        $pliegosobservacion->update(['fase_autorizacion' => 'En validación', 'nivel_autorizacion' => $nivel_autorizacion]);
        setMessage($request->estatus == 'Aprobado' ?
            'La aprobación ha sido registrada y se ha enviado a validación del superior.' :
            'El rechazo ha sido registrado.'
        );

        $titulo = 'Registro de la calificación y conclusión de la atención de la recomendación de la acción No. '.$pliegosobservacion->accion->numero.' de la Auditoría No. '.$pliegosobservacion->accion->auditoria->numero_auditoria;

        $mensaje = '<strong>Estimado(a) '.$director->name.', '.$director->puesto.':</strong><br>'
                            .auth()->user()->name.', '.auth()->user()->puesto.
                            '; Ha sido registrada la calificación y la conclusión de la atención de la recomendación de la Acción No. '.$pliegosobservacion->accion->numero.' de la Auditoría No. '.$pliegosobservacion->accion->auditoria->numero_auditoria.
                            ', por lo que se requiere realice la validación oportuna en el módulo Seguimiento.';
            auth()->user()->insertNotificacion($titulo, $mensaje, now(), $director->unidad_administrativa_id, $director->id);

        return redirect()->route('recomendacionesatencion.index');
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
