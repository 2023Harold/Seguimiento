<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use App\Models\AuditoriaAccion;
use App\Models\Movimientos;
use App\Models\Segpras;
use App\Models\User;
use Illuminate\Http\Request;

class PrasTurnoController extends Controller
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
        //dd(getSession('prasauditoriaaccion_id'));
        $accion=AuditoriaAccion::find(getSession('prasauditoriaaccion_id'));
        $auditoria=$accion->auditoria;
        $pras=new Segpras();

        return view('prasturnos.form',compact('pras','accion','auditoria'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
       
        $auditoria = Auditoria::find(getSession('prasauditoria_id'));
        mover_archivos($request, ['oficio_remision'], null);
        $firmante = User::where('unidad_administrativa_id', '122000')->first();
        $request->merge([
            'fecha_elaboracion_oficio' => today(),
            'auditoria_id' => $auditoria->id,
            //'cedula_preliminar_id' => getSession('cedula_preliminar_id'),
            'entidad_fiscalizable_id' => $auditoria->entidad_fiscalizable_id,
            'usuario_firmante_id' => $firmante->id,
            'usuario_creacion_id' => auth()->id(),
            'accion_id'=>getSession('prasauditoriaaccion_id'),
    ]);
    $pras= Segpras::create($request->all());

    Movimientos::create([
        'tipo_movimiento' => 'Registro del PRAS',
        'accion' => 'PRAS',
        'accion_id' => $pras->id,
        'estatus' => 'Aprobado',
        'usuario_creacion_id' => auth()->id(),
        'usuario_asignado_id' => auth()->id(),
    ]);        

    if (strlen($pras->nivel_autorizacion) == 3) {
        $nivel_autorizacion = $pras->nivel_autorizacion;
    } else {
        $nivel_autorizacion = substr(auth()->user()->unidad_administrativa_id, 0, 4);
    }
   
    $pras->update(['fase_autorizacion' =>  'En revisión', 'nivel_autorizacion' => $nivel_autorizacion]);      

    $titulo = 'Validación de los datos de radicación';
    $mensaje = '<strong>Estimado (a) ' . auth()->user()->director->name . ', ' . auth()->user()->director->puesto . ':</strong><br>
                Ha sido registrada el PRAS de la auditoría No. ' . $pras->auditoria->numero_auditoria . ', por parte del ' . 
                auth()->user()->puesto.' '.auth()->user()->name . ', por lo que se requiere realice la validación.';

    auth()->user()->insertNotificacion($titulo, $mensaje, now(), auth()->user()->director->unidad_administrativa_id,auth()->user()->director->id);


    setMessage('Se han guardado los datos correctamente');

    return redirect()->route('prasacciones.index');
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
    public function edit(Segpras $pras )
    {
     //dd(getSession('prasauditoriaaccion_id'));
     $accion=AuditoriaAccion::find(getSession('prasauditoriaaccion_id'));
     $auditoria=$accion->auditoria;
     
     

     return view('prasturnos.form',compact('pras','accion','auditoria'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Segpras $pras)
    {
              
        $auditoria = Auditoria::find(getSession('prasauditoria_id'));
        mover_archivos($request, ['oficio_remision'], $pras);
        $firmante = User::where('unidad_administrativa_id', '122000')->first();
        $request->merge([
            'fecha_elaboracion_oficio' => today(),
            'auditoria_id' => $auditoria->id,
            //'cedula_preliminar_id' => getSession('cedula_preliminar_id'),
            'entidad_fiscalizable_id' => $auditoria->entidad_fiscalizable_id,
            'usuario_firmante_id' => $firmante->id,
            'usuario_creacion_id' => auth()->id(),
            'accion_id'=>getSession('prasauditoriaaccion_id'),
    ]);
    $pras->update($request->all());

    Movimientos::create([
        'tipo_movimiento' => 'Registro del PRAS',
        'accion' => 'PRAS',
        'accion_id' => $pras->id,
        'estatus' => 'Aprobado',
        'usuario_creacion_id' => auth()->id(),
        'usuario_asignado_id' => auth()->id(),
    ]);        

    if (strlen($pras->nivel_autorizacion) == 3) {
        $nivel_autorizacion = $pras->nivel_autorizacion;
    } else {
        $nivel_autorizacion = substr(auth()->user()->unidad_administrativa_id, 0, 4);
    }
   
    $pras->update(['fase_autorizacion' =>  'En revisión', 'nivel_autorizacion' => $nivel_autorizacion]);      

    $titulo = 'Validación de los datos de radicación';
    $mensaje = '<strong>Estimado (a) ' . auth()->user()->director->name . ', ' . auth()->user()->director->puesto . ':</strong><br>
                Ha sido registrada el PRAS de la auditoría No. ' . $pras->auditoria->numero_auditoria . ', por parte del ' . 
                auth()->user()->puesto.' '.auth()->user()->name . ', por lo que se requiere realice la validación.';

    auth()->user()->insertNotificacion($titulo, $mensaje, now(), auth()->user()->director->unidad_administrativa_id,auth()->user()->director->id);


    setMessage('Se han guardado los datos correctamente');

    return redirect()->route('prasacciones.index');
    
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
