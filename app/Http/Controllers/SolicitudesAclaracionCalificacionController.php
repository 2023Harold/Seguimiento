<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use App\Models\AuditoriaAccion;
use App\Models\Movimientos;
use App\Models\SolicitudesAclaracion;
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
        $auditoria = Auditoria::find(getSession('solicitudesaclaracionauditoria_id'));
        $accion = AuditoriaAccion::find(getSession('solicitudauditoriaaccion_id'));
        $solicitudes = SolicitudesAclaracion::where('accion_id',getSession('solicitudauditoriaaccion_id'))->get();
           
        return view('solicitudesaclaracioncalificacion.index',compact('solicitudes','auditoria','accion','request'));
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
        $documentos=SolicitudesAclaracionDocumento::where('solicitud_aclaracion_id',$solicitud->id)->paginate(10);        
        
        return view('solicitudesaclaracioncalificacion.show',compact('documentos'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(SolicitudesAclaracion $solicitud)
    {
        $accion=AuditoriaAccion::find(getSession('solicitudauditoriaaccion_id'));
        $auditoria=$accion->auditoria;    
        setSession('solicitudaclaracion_id',$solicitud->id); 

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
        $documentos = SolicitudesAclaracionDocumento::where('solicitud_aclaracion_id',$solicitud->id)->get();
        if($documentos->count()==0){
            setMessage('No se ha capturado información en el apartado de listado de documentos.','error');
            
            return back()->withInput();
        }
        
        $request['concluido']='Si';
        
        if ($request->cumple=='Atendida') {
            $request['monto_solventado'] = $solicitud->accion->monto_aclarar;
        }else if($request->cumple=='No Atendida'){
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

        $solicitud->update($request->all());

         Movimientos::create([
            'tipo_movimiento' => 'Registro de atención de la solicitud de aclaración',
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
    
        $solicitud->update(['fase_autorizacion' =>  'En revisión 01', 'nivel_autorizacion' => $nivel_autorizacion]);      

        $titulo = 'Revisión del registro de la atención de la solicitud de aclaración de la acción No. '.$solicitud->accion->numero.' de la Auditoría No. '.$solicitud->accion->auditoria->numero_auditoria;
        $mensaje = '<strong>Estimado (a) ' . auth()->user()->lider->name . ', ' . auth()->user()->lider->puesto . ':</strong><br>
                    Ha sido registrada la atención de la solicitud de aclaración de la acción No. '.$solicitud->accion->numero.' de la Auditoría No. '.$solicitud->accion->auditoria->numero_auditoria . ', por parte del ' . 
                    auth()->user()->puesto.' '.auth()->user()->name . ', por lo que se requiere realice la revisión.';

        auth()->user()->insertNotificacion($titulo, $mensaje, now(), auth()->user()->lider->unidad_administrativa_id,auth()->user()->lider->id);
     
        return redirect()->route('solicitudesaclaracioncalificacion.index');
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
