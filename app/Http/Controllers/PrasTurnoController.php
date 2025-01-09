<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use App\Models\AuditoriaAccion;
use App\Models\Directorio\UserDirectorio;
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
    public function index(Request $request)
    {
        $auditoria = Auditoria::find(getSession('auditoria_id'));
        $accion = AuditoriaAccion::find(getSession('prasauditoriaaccion_id'));
        $prass = Segpras::where('accion_id',getSession('prasauditoriaaccion_id'))->get();
        //dd($prass);

        return view('prasturnos.index',compact('prass','auditoria','accion'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $accion=AuditoriaAccion::find(getSession('prasauditoriaaccion_id'));
        $auditoria=$accion->auditoria;
        $pras=new Segpras();
        $usuariodirectorio=null;
        

        if(!empty($auditoria->entidad_fiscalizable_id)){
            $usuariodirectorio=UserDirectorio::where('entidad_fiscalizable_id',$auditoria->entidad_fiscalizable_id)
            ->where('cargo_asociado', 'Contralor Interno')
            ->where('siglas_cargo_asociado', 'OIC')
            ->where('estatus', 'Activo')->first();
        }
       
        $nombreuseroic=null;
        if (!empty($usuariodirectorio)) {
            $nombreuseroic=$usuariodirectorio->name.' '.$usuariodirectorio->primer_apellido.' '.$usuariodirectorio->segundo_apellido;
        }

        return view('prasturnos.form',compact('pras','accion','auditoria','nombreuseroic'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $auditoria = Auditoria::find(getSession('auditoria_id'));
        mover_archivos($request, ['oficio_remision'], null);
        $firmante = User::where('unidad_administrativa_id', '122000')->first();
        $request->merge([
            'fecha_elaboracion_oficio' => today(),
            'entidad_fiscalizable_id' => $auditoria->entidad_fiscalizable_id,
            'usuario_firmante_id' => $firmante->id,
            'usuario_creacion_id' => auth()->id(),
            'accion_id'=>getSession('prasauditoriaaccion_id'),
            'auditoria_id'=>$auditoria->id,
    ]);
    $pras= Segpras::create($request->all());

    Movimientos::create([
        'tipo_movimiento' => 'Registro del turno del PRAS',
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

    $titulo = 'Revisión del registro del turno del PRAS  de la Acción No. '.$pras->accion->numero.' de la Auditoría No. '.$pras->accion->auditoria->numero_auditoria;
    $mensaje = '<strong>Estimado (a) ' . auth()->user()->jefe->name . ', ' . auth()->user()->jefe->puesto . ':</strong><br>
                Ha sido registrado el turno del PRAS  de la Acción No. '.$pras->accion->numero.' de la Auditoría No. '.$pras->accion->auditoria->numero_auditoria . ', por parte del ' .
                auth()->user()->puesto.' '.auth()->user()->name . ', por lo que se requiere realice la revisión.';

    auth()->user()->insertNotificacion($titulo, $mensaje, now(), auth()->user()->jefe->unidad_administrativa_id,auth()->user()->jefe->id);


    setMessage('Se han guardado los datos correctamente');

    return redirect()->route('prasturno.index');
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

        $auditoria = Auditoria::find(getSession('auditoria_id'));
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
        'tipo_movimiento' => 'Registro del turno del PRAS',
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

    $titulo = 'Revisión del registro del turno del PRAS  de la Acción No. '.$pras->accion->numero.' de la Auditoría No. '.$pras->accion->auditoria->numero_auditoria;
    $mensaje = '<strong>Estimado (a) ' . auth()->user()->jefe->name . ', ' . auth()->user()->jefe->puesto . ':</strong><br>
                Ha sido registrado el turno del PRAS  de la Acción No. '.$pras->accion->numero.' de la Auditoría No. '.$pras->accion->auditoria->numero_auditoria . ', por parte del ' .
                auth()->user()->puesto.' '.auth()->user()->name . ', por lo que se requiere realice la revisión.';

    auth()->user()->insertNotificacion($titulo, $mensaje, now(), auth()->user()->jefe->unidad_administrativa_id,auth()->user()->jefe->id);


    setMessage('Se han guardado los datos correctamente');

    return redirect()->route('prasturno.index');

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
