<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use App\Models\AuditoriaAccion;
use App\Models\Movimientos;
use App\Models\SolicitudesAclaracion;
use App\Models\SolicitudesAclaracionContestacion;
use App\Models\SolicitudesAclaracionDocumento;
use Illuminate\Http\Request;

class SolicitudesAclaracionCalificacionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
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
    public function show(SolicitudesAclaracion $solicitud)
    {       
        $accion=AuditoriaAccion::find(getSession('solicitudesauditoriaaccion_id'));
        $auditoria=$accion->auditoria;     

        return view('solicitudesaclaracioncalificacion.show',compact('solicitud','accion','auditoria'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(SolicitudesAclaracion $solicitud)
    {
        $accion=AuditoriaAccion::find(getSession('solicitudesauditoriaaccion_id'));
        $auditoria=$accion->auditoria;    

        return view('solicitudesaclaracioncalificacion.form',compact('solicitud','accion','auditoria'));
        //dd($solicitud);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SolicitudesAclaracion $solicitud)
    {
        $director=auth()->user()->director;
        $documentos = SolicitudesAclaracionDocumento::where('solicitudaclaracion_id',$solicitud->id)->get();
        if($documentos->count()==0){
            setMessage('No se ha capturado información en el apartado de listado de documentos.','error');
            
            return back()->withInput();
        }
        $contestaciones = SolicitudesAclaracionContestacion::where('solicitudaclaracion_id',$solicitud->id)->get();
        if($contestaciones->count()==0){
            setMessage('No se ha capturado información en el apartado de contestaciones.','error');
            
            return back()->withInput();
        }        
        if ($request->calificacion_atencion=='Solventada') {
            $request['monto_solventado'] = $solicitud->accion->monto_aclarar;
        }else if($request->calificacion_atencion=='No Solventada'){
            $request['monto_solventado'] = 0.00;
        }else{
            $monto_aclarar = $solicitud->accion->monto_aclarar;
            $montostrpesos = str_replace('$','',$request->monto_solventado); 
            $montostrcomas = str_replace(',','',$montostrpesos); 
            $monto=(float) $montostrcomas;
            //dd($monto,$request->monto_solventado);
            if($monto==0.00){
                setMessage('El monto solventado debe ser mayor a $0.00.','error');
            
                return back()->withInput();
            }
            if($monto > $monto_aclarar){
                setMessage('El monto solventado debe ser menor al monto por aclarar','error');
            
                return back()->withInput();
            }
            $request['monto_solventado'] = $monto;
        }

        $request['concluido']='Si';
        $solicitud->update($request->all());

         Movimientos::create([
            'tipo_movimiento' => 'Registro de la calificación y la conclusión de la atención de la solicitud de aclaración',
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
    
        $solicitud->update(['fase_autorizacion' =>  'En validación', 'nivel_autorizacion' => $nivel_autorizacion]);      
        
        setMessage($request->estatus == 'Aprobado' ?
            'La aprobación ha sido registrada y se ha enviado a validación del superior.' :
            'El rechazo ha sido registrado.'
        );

        $titulo = 'Registro de la calificación y conclusión de la atención de la solicitud de aclaración de la acción No. '.$solicitud->accion->numero.' de la Auditoría No. '.$solicitud->accion->auditoria->numero_auditoria;
        $mensaje = '<strong>Estimado (a) ' . auth()->user()->lider->name . ', ' . auth()->user()->lider->puesto . ':</strong><br>
                    Ha sido registrada la calificación y la conclusión de la atención de la solicitud de aclaración de la acción No. '.$solicitud->accion->numero.' de la Auditoría No. '.$solicitud->accion->auditoria->numero_auditoria . ', por parte del ' . 
                    auth()->user()->puesto.' '.auth()->user()->name . ', por lo que se requiere realice la revisión.';

        auth()->user()->insertNotificacion($titulo, $mensaje, now(), auth()->user()->lider->unidad_administrativa_id,auth()->user()->lider->id);
     
        return redirect()->route('solicitudesaclaracionatencion.index');
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
