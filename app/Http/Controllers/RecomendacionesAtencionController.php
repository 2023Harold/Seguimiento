<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use App\Models\AuditoriaAccion;
use App\Models\Movimientos;
use App\Models\Recomendaciones;
use App\Models\User;
use Illuminate\Http\Request;

class RecomendacionesAtencionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $auditoria = Auditoria::find(getSession('recomendacionesauditoria_id'));
        $accion = AuditoriaAccion::find(getSession('recomendacionesauditoriaaccion_id'));
        $recomendaciones = Recomendaciones::where('accion_id',getSession('recomendacionesauditoriaaccion_id'))->get();

        return view('recomendacionesatencion.index',compact('recomendaciones','auditoria','accion'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $accion=AuditoriaAccion::find(getSession('recomendacionesauditoriaaccion_id'));
        $auditoria=$accion->auditoria;
        $recomendacion=new Recomendaciones();
      

        return view('recomendacionesatencion.form',compact('recomendacion','accion','auditoria'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $auditoria = Auditoria::find(getSession('recomendacionesauditoria_id'));
        mover_archivos($request, ['oficio_contestacion']);
        $firmante = User::where('unidad_administrativa_id', '122000')->first();
        $request->merge([
            'auditoria_id' => $auditoria->id,
            'usuario_creacion_id' => auth()->id(),
            'accion_id'=>getSession('recomendacionesauditoriaaccion_id'),
            'consecutivo' => 1,
            'departamento_responsable_id'=> auth()->user()->unidad_administrativa_id,
    ]);
    $recomendacion= Recomendaciones::create($request->all());

    Movimientos::create([
        'tipo_movimiento' => 'Registro de atencion de la recomendación',
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


    setMessage('Se han guardado los datos correctamente');

    return redirect()->route('recomendacionesatencion.index');

        // return redirect()->route('seguimientoauditoriaacciones.index');
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
        $accion=AuditoriaAccion::find(getSession('recomendacionesauditoriaaccion_id'));
        $auditoria=$accion->auditoria;     

        return view('recomendacionesatencion.form',compact('recomendacion','accion','auditoria'));
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
           
        $auditoria = Auditoria::find(getSession('recomendacionesauditoria_id'));
        mover_archivos($request, ['oficio_remision'], $recomendacion);
        $firmante = User::where('unidad_administrativa_id', '122000')->first();
        $request->merge([
            'auditoria_id' => $auditoria->id,
            'usuario_creacion_id' => auth()->id(),
            'accion_id'=>getSession('recomendacionesauditoriaaccion_id'),
            'consecutivo' => 1,
            'departamento_responsable_id'=> auth()->user()->unidad_administrativa_id,
        ]);
    $recomendacion->update($request->all());

    Movimientos::create([
        'tipo_movimiento' => 'Registro de atencion de la recomendación',
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


    setMessage('Se han guardado los datos correctamente');

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
