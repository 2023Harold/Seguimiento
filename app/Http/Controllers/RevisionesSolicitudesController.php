<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use App\Models\AuditoriaAccion;
use App\Models\Revisiones;
use App\Models\User;
use Illuminate\Http\Request;

class RevisionesSolicitudesController extends Controller
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
    public function create(Request $request)
    {
        $comentario = new Revisiones();
        $accion = 'Agregar';
        $acciones=AuditoriaAccion::find(getSession('solicitudesauditoriaaccion_id'));
        $tipo = $request->query('tipo'); // tipo para identificar el archivo solo aplica para 
        $auditoria = Auditoria::find(getSession('auditoria_id'));

        return view('revisionessolicitudes.form', compact('comentario', 'accion','auditoria', 'acciones', 'tipo'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $accion = AuditoriaAccion::find(getSession('solicitudesauditoriaaccion_id'));
        $auditoria = Auditoria::find(getSession('auditoria_id'));

        $request->merge([
            'de_usuario_id'=>auth()->user()->id,
            'para_usuario_id'=>intval($accion->analista_asignado_id),
            'accion'=>'Solicitud de Aclaración',
            'accion_id'=>$accion->id,
            'estatus'=>'Pendiente',
            'usuario_creacion_id'=>auth()->user()->id,
        ]);

        Revisiones::create($request->all());      
        $titulo='Se ha realizado un comentario en la solicitud de aclaración de la Acción No. '.$accion->numero.' de la Auditoría No. '.$accion->auditoria->numero_auditoria;
        
        $titular=User::where('siglas_rol','TUS')->first();
        if(getSession('cp')==2022){
            $director = $accion->auditoria->directorasignado;
            $jefe=$accion->depaasignado;
            $lider=$accion->lider;
            $analista=$accion->analista;
        }else{
            $director = $auditoria->directorasignado;
            $jefe = $auditoria->jefedepartamentoencargado;
            $analista = $auditoria->analistacp;
            $lider = $auditoria->lidercp; 
        }
        
        if(auth()->user()->siglas_rol=='AS'){
            auth()->user()->insertNotificacion($titulo, $this->mensajeComentario($titular->name,$titular->puesto), now(), $titular->unidad_administrativa_id, $titular->id);           
            auth()->user()->insertNotificacion($titulo, $this->mensajeComentario($director->name,$director->puesto), now(), $director->unidad_administrativa_id, $director->id);
            auth()->user()->insertNotificacion($titulo, $this->mensajeComentario($jefe->name,$jefe->puesto), now(), $jefe->unidad_administrativa_id, $jefe->id);
            auth()->user()->insertNotificacion($titulo, $this->mensajeComentario($lider->name,$lider->puesto), now(), $lider->unidad_administrativa_id, $lider->id);
            auth()->user()->insertNotificacion($titulo, $this->mensajeComentario($analista->name,$analista->puesto), now(), $analista->unidad_administrativa_id, $analista->id);
        }
        if(auth()->user()->siglas_rol=='TUS'){
            auth()->user()->insertNotificacion($titulo, $this->mensajeComentario($director->name,$director->puesto), now(), $director->unidad_administrativa_id, $director->id);
            auth()->user()->insertNotificacion($titulo, $this->mensajeComentario($jefe->name,$jefe->puesto), now(), $jefe->unidad_administrativa_id, $jefe->id);
            auth()->user()->insertNotificacion($titulo, $this->mensajeComentario($lider->name,$lider->puesto), now(), $lider->unidad_administrativa_id, $lider->id);
            auth()->user()->insertNotificacion($titulo, $this->mensajeComentario($analista->name,$analista->puesto), now(), $analista->unidad_administrativa_id, $analista->id);
        }
       elseif(auth()->user()->siglas_rol=='DS'){
            auth()->user()->insertNotificacion($titulo, $this->mensajeComentario($titular->name,$titular->puesto), now(), $titular->unidad_administrativa_id, $titular->id);    
            auth()->user()->insertNotificacion($titulo, $this->mensajeComentario($jefe->name,$jefe->puesto), now(), $jefe->unidad_administrativa_id, $jefe->id);
            auth()->user()->insertNotificacion($titulo, $this->mensajeComentario($lider->name,$lider->puesto), now(), $lider->unidad_administrativa_id, $lider->id);
            auth()->user()->insertNotificacion($titulo, $this->mensajeComentario($analista->name,$analista->puesto), now(), $analista->unidad_administrativa_id, $analista->id);
       }
       elseif (auth()->user()->siglas_rol=='JD') {
            auth()->user()->insertNotificacion($titulo, $this->mensajeComentario($titular->name,$titular->puesto), now(), $titular->unidad_administrativa_id, $titular->id);           
            auth()->user()->insertNotificacion($titulo, $this->mensajeComentario($director->name,$director->puesto), now(), $director->unidad_administrativa_id, $director->id);
            auth()->user()->insertNotificacion($titulo, $this->mensajeComentario($lider->name,$lider->puesto), now(), $lider->unidad_administrativa_id, $lider->id);
            auth()->user()->insertNotificacion($titulo, $this->mensajeComentario($analista->name,$analista->puesto), now(), $analista->unidad_administrativa_id, $analista->id);
       } 
       elseif (auth()->user()->siglas_rol=='LP') {
            auth()->user()->insertNotificacion($titulo, $this->mensajeComentario($titular->name,$titular->puesto), now(), $titular->unidad_administrativa_id, $titular->id);           
            auth()->user()->insertNotificacion($titulo, $this->mensajeComentario($director->name,$director->puesto), now(), $director->unidad_administrativa_id, $director->id);
            auth()->user()->insertNotificacion($titulo, $this->mensajeComentario($jefe->name,$jefe->puesto), now(), $jefe->unidad_administrativa_id, $jefe->id);
            auth()->user()->insertNotificacion($titulo, $this->mensajeComentario($analista->name,$analista->puesto), now(), $analista->unidad_administrativa_id, $analista->id);
       }elseif(auth()->user()->siglas_rol=='STAFF'){
        auth()->user()->insertNotificacion($titulo, $this->mensajeComentario($titular->name,$titular->puesto), now(), $titular->unidad_administrativa_id, $titular->id);           
        auth()->user()->insertNotificacion($titulo, $this->mensajeComentario($director->name,$director->puesto), now(), $director->unidad_administrativa_id, $director->id);
        auth()->user()->insertNotificacion($titulo, $this->mensajeComentario($jefe->name,$jefe->puesto), now(), $jefe->unidad_administrativa_id, $jefe->id);
        auth()->user()->insertNotificacion($titulo, $this->mensajeComentario($lider->name,$lider->puesto), now(), $lider->unidad_administrativa_id, $lider->id);
        auth()->user()->insertNotificacion($titulo, $this->mensajeComentario($analista->name,$analista->puesto), now(), $analista->unidad_administrativa_id, $analista->id);
        }   
        
        setMessage('se ha agregado el comentario correctamente.');

        return view('layouts.close');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Revisiones $comentario)
    {
        return view('revisionessolicitudes.show', compact('comentario'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Revisiones $comentario)
    {
        setSession('comentario_id',$comentario->id);

        return redirect()->route('revisionessolicitudesatencion.create');
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

    private function mensajeComentario(String $nombre, String $puesto)
    {
        $mensaje = '<strong>Estimado(a) '.$nombre.', '.$puesto.':</strong><br>'
                    .'Se registro un comentario por parte del '.auth()->user()->puesto.'; '.auth()->user()->name.', por lo que se debe atender.';    

        return $mensaje;
    }
}
