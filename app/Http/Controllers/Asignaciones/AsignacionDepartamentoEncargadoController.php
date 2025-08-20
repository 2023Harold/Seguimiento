<?php

namespace App\Http\Controllers\Asignaciones;

use App\Http\Controllers\Controller;
use App\Models\Auditoria;
use App\Models\AuditoriaAccion;
use App\Models\CatalogoUnidadesAdministrativas;
use App\Models\User;
use Illuminate\Http\Request;

class AsignacionDepartamentoEncargadoController extends Controller
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
    public function edit(Auditoria $auditoria)
    {
              
        $unidades = auth()->user()->unidadAdministrativa->departamentos->prepend('Seleccionar una opción', '');         
        $acciondep ='Asignación del Departamento Encargado de la Auditoría';   
             
               
        return view('asignaciondepartamentoencargado.form', compact('auditoria','unidades','acciondep'));  
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Auditoria $auditoria)
    {
        
            $auditoria->update($request->all());
            $titulo = 'Asignación de auditoría';
            $mensaje = '<strong>Estimado(a) ' . $request->nombre . ', ' . $request->cargo . '.</strong><br>Se le ha asignado la auditoría No.  ' . $auditoria->numero_auditoria . ', por parte del Titular, por lo que se requiere realice la radicación y comparecencia.';
            auth()->user()->insertNotificacion($titulo, $mensaje, now(), $request->departamento_encargado_id, $request->usuario_id);
            $mensaje='Se ha realizado la asignación del departamento encargado de la auditoría correctamente.';
            
            if(!empty($request->auditoria_completa)&&$request->auditoria_completa=='X'){                
                foreach ($auditoria->acciones as $accion) {
                    $departamento=CatalogoUnidadesAdministrativas::where('id',$request->departamento_encargado_id)->first();                   
                    $user=User::where('unidad_administrativa_id',$departamento->id)->first();
        
                    $requestaccion= new Request();
                    $requestaccion['departamento_asignado_id']=$departamento->id;
                    $requestaccion['departamento_asignado']=$departamento->descripcion;
                    $accion->update($requestaccion->all());
    
                    $titulo = 'Asignación de la accion No. '.$accion->numero.'  de la auditoría '.$auditoria->numero_auditoria;
                    $mensaje = '<strong>Estimado(a) ' . $user->name . ', ' . $user->puesto . '.</strong><br>Se le ha asignado la auditoría No.  ' . $auditoria->numero_auditoria . ', por parte del '.auth()->user()->puesto. ' '.auth()->user()->name.', por lo que se requiere realice la asignación oportuna del equipo de analistas y lider de proyecto, en el módulo de Asignación.';
                    auth()->user()->insertNotificacion($titulo, $mensaje, now(), $departamento->id, $user->id);
                }
                $auditoria->update(['asignacion_departamentos'=>'Si']);
                $mensaje='Se ha realizado la asignación completa de la auditoría al departamento encargado.';
            }           
            
            setMessage($mensaje);           

        return redirect()->route('asignaciondepartamento.index',$auditoria);  
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
