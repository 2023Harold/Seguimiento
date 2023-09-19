<?php

namespace App\Http\Controllers;

use App\Models\AuditoriaAccion;
use App\Models\Movimientos;
use App\Models\Recomendaciones;
use App\Models\RecomendacionesContestacion;
use App\Models\RecomendacionesDocumento;
use Illuminate\Http\Request;

class RecomendacionesAtencionCalificacionController extends Controller
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
    public function show(Recomendaciones $recomendacion)
    {        
        $accion=AuditoriaAccion::find(getSession('recomendacionesauditoriaaccion_id'));
        $auditoria=$accion->auditoria;     

        return view('recomendacionesatencioncalificacion.show',compact('recomendacion','accion','auditoria'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Recomendaciones $recomendacion)
    {
        $accion=AuditoriaAccion::find(getSession('recomendacionesauditoriaaccion_id'));
        $auditoria=$accion->auditoria;     

        return view('recomendacionesatencioncalificacion.form',compact('recomendacion','accion','auditoria'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Recomendaciones $recomendacion)
    {
        $documentos = RecomendacionesDocumento::where('recomendacion_id',$recomendacion->id)->get();
        if($documentos->count()==0){
            setMessage('No se ha capturado información en el apartado de listado de documentos.','error');
            
            return back()->withInput();
        }
        $contestaciones = RecomendacionesContestacion::where('recomendacion_id',$recomendacion->id)->get();
        if($contestaciones->count()==0){
            setMessage('No se ha capturado información en el apartado de contestaciones.','error');
            
            return back()->withInput();
        }
        $request['concluido']='Si';
        $recomendacion->update($request->all());

         Movimientos::create([
            'tipo_movimiento' => 'Registro de atención de la recomendación',
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
    
        $recomendacion->update(['fase_autorizacion' =>  'En revisión 01', 'nivel_autorizacion' => $nivel_autorizacion]);      

        $titulo = 'Revisión del registro de la atención de la recomendación de la acción No. '.$recomendacion->accion->numero.' de la Auditoría No. '.$recomendacion->accion->auditoria->numero_auditoria;
        $mensaje = '<strong>Estimado (a) ' . auth()->user()->lider->name . ', ' . auth()->user()->lider->puesto . ':</strong><br>
                    Ha sido registrada la atención de la recomendación de la acción No. '.$recomendacion->accion->numero.' de la Auditoría No. '.$recomendacion->accion->auditoria->numero_auditoria . ', por parte del ' . 
                    auth()->user()->puesto.' '.auth()->user()->name . ', por lo que se requiere realice la revisión.';

        auth()->user()->insertNotificacion($titulo, $mensaje, now(), auth()->user()->lider->unidad_administrativa_id,auth()->user()->lider->id);
        
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
