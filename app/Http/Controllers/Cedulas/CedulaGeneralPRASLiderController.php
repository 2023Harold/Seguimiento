<?php


namespace App\Http\Controllers\Cedulas;

use App\Http\Controllers\Controller;
use App\Models\Auditoria;
use App\Models\AuditoriaAccion;
use App\Models\Cedula;
use App\Models\Movimientos;
use Illuminate\Http\Request;

class CedulaGeneralPRASLiderController extends Controller
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
    public function edit(Cedula $cedula)
    {
        $auditoria = Auditoria::find(getSession('auditoria_id'));
        $nombre=$cedula->cedula;
        
        return view('cedulageneralprasaprobarlider.form',compact('nombre','auditoria','cedula'));
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cedula $cedula)
    {
       
         $auditoria = Auditoria::find(getSession('auditoria_id'));
         $accionesanalistas=AuditoriaAccion::whereNull('aprobar_cedpras_lider')->where('segauditoria_id',$auditoria->id)->get();   
         $lideres=array_unique($accionesanalistas->pluck('lider_asignado_id', 'id')->toArray()); 
         
         
         if(count($lideres)>1){
             if($request->estatus=='Aprobado'){
                 AuditoriaAccion::where('lider_asignado_id',auth()->user()->id)->where('segauditoria_id',$auditoria->id)->update(['aprobar_cedpras_lider'=>'Si']);
             }else{
                 AuditoriaAccion::where('segauditoria_id',$auditoria->id)->update(['aprobar_cedpras_lider'=>null]);
             }
             
             Movimientos::create([
                 'tipo_movimiento' => 'Revisión de la Cédula General PRAS',
                 'accion' => 'Cédula General PRAS',
                 'accion_id' => $auditoria->id,
                 'estatus' => $request->estatus,
                 'usuario_creacion_id' => auth()->id(),
                 'usuario_asignado_id' => auth()->id(),
                 'motivo_rechazo' => $request->motivo_rechazo,
             ]);
 
             setMessage('Se ha '.$request->estatus.' la Cédula General PRAS.');
         }else{
 
             if($request->estatus=='Aprobado'){
                 AuditoriaAccion::where('lider_asignado_id',auth()->user()->id)->where('segauditoria_id',$auditoria->id)->update(['aprobar_cedpras_lider'=>'Si']);
                 
                 if (strlen($cedula->nivel_autorizacion) == 3) {
                     $nivel_autorizacion = $cedula->nivel_autorizacion;
                 } else {
                     $nivel_autorizacion = substr(auth()->user()->unidad_administrativa_id, 0, 4);
                 }
         
                 $cedula->update(['fase_autorizacion' =>  'En revisión', 'nivel_autorizacion' => $nivel_autorizacion]);
         
                 $titulo = 'Revisión del la Cédula General PRAS de la Auditoría No. '.$auditoria->numero_auditoria;
                 $mensaje = '<strong>Estimado (a) ' . auth()->user()->jefe->name . ', ' . auth()->user()->jefe->puesto . ':</strong><br>
                             Ha sido registrada la Cédula General PRAS de la Auditoría No. '.$auditoria->numero_auditoria . ', por parte del ' .
                             auth()->user()->puesto.' '.auth()->user()->name . ', por lo que se requiere realice la revisión.';
     
                 auth()->user()->insertNotificacion($titulo, $mensaje, now(), auth()->user()->jefe->unidad_administrativa_id,auth()->user()->jefe->id);
 
                 $msj='Se ha enviado a revisión la Cédula General PRAS.';
             }else{
                 AuditoriaAccion::where('segauditoria_id',$auditoria->id)->update(['aprobar_cedpras_lider'=>null]);
 
                 $msj='Se ha rechazado a revisión la Cédula General PRAS.';
             }
             
             Movimientos::create([
                 'tipo_movimiento' => 'Revisión de la Cédula General PRAS',
                 'accion' => 'Cédula General PRAS',
                 'accion_id' => $auditoria->id,
                 'estatus' => $request->estatus,
                 'usuario_creacion_id' => auth()->id(),
                 'usuario_asignado_id' => auth()->id(),
                 'motivo_rechazo' => $request->motivo_rechazo,
             ]);
 
            
             
             setMessage($msj);
         }
 
         return view('layouts.close');
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
