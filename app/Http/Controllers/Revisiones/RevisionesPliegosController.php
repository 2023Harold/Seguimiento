<?php

namespace App\Http\Controllers\Revisiones;

use App\Http\Controllers\Controller;
use App\Models\Auditoria;
use App\Models\AuditoriaAccion;
use App\Models\AuditoriaUsuarios;
use App\Models\PliegosObservacion;
use App\Models\Revisiones;
use App\Models\User;
use DB;
use Illuminate\Http\Request;

class RevisionesPliegosController extends Controller
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
        $acciones=AuditoriaAccion::find(getSession('pliegosobservacionauditoriaaccion_id'));
        $tipo = $request->query('tipo'); // tipo para identificar el archivo solo aplica para 
        $auditoria = Auditoria::find(getSession('auditoria_id'));
        // dd($tipo);

        return view('comentarios.revisionespliegos.form', compact('comentario', 'accion', 'auditoria', 'acciones', 'tipo'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $accion = AuditoriaAccion::find(getSession('pliegosobservacionauditoriaaccion_id'));

        $auditoria = Auditoria::find(getSession('auditoria_id'));
        $staffA = AuditoriaUsuarios::select('segusers.id','segusers.name','segusers.puesto', 'segusers.unidad_administrativa_id', 'segusers.siglas_rol', 'segusers.estatus',   
                                            DB::raw("(case when(segusers.id = segauditoria_usuarios.staff_id) THEN segusers.name ELSE NULL END) AS staffAsignado01"),
                                            )->join('segusers', 'segusers.id', '=', 'segauditoria_usuarios.staff_id')->where('auditoria_id', $auditoria->id)->get()->toArray();   
        
        $request->merge([
            'de_usuario_id'=>auth()->user()->id,
            'para_usuario_id'=>intval($accion->analista_asignado_id),
            'accion'=>'Pliego de Observación',
            'accion_id'=>$accion->id,
            'estatus'=>'Pendiente',
            'usuario_creacion_id'=>auth()->user()->id,
        ]);
        if(auth()->user()->siglas_rol=='ATUS' || auth()->user()->siglas_rol=='DS'){
            if($request->tipo == 'Analisis'){
                $request->merge([
                    'universo_rev'=> optional($accion->pliegosobservacion)->analisis,
                ]);

            }elseif($request->tipo == 'Conclusión'){
                $request->merge([
                    'universo_rev'=> optional($accion->pliegosobservacion)->conclusion,
                ]);
            }elseif($request->tipo == 'Listado Documentos'){
                $request->merge([
                    'universo_rev'=> optional($accion->pliegosobservacion)->listado_documentos,
                ]);
            }
        }
        
        Revisiones::create($request->all());      
        $titulo='Se ha realizado un comentario en el pliego de observación de la Acción No. '.$accion->numero.' de la Auditoría No. '.$accion->auditoria->numero_auditoria;
        
            $titular=User::where('siglas_rol','TUS')->first();
            $AsistenteTitular=User::where('siglas_rol','ATUS')->first();
            
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
        
        $url = route('pliegosobservacionatencion.index');
        if(auth()->user()->siglas_rol=='AS'){
            auth()->user()->insertNotificacion($titulo, $this->mensajeComentario($titular->name,$titular->puesto), now(), $titular->unidad_administrativa_id, $titular->id, GenerarLlave($accion).'/Comentario', $url);           
            auth()->user()->insertNotificacion($titulo, $this->mensajeComentario($director->name,$director->puesto), now(), $director->unidad_administrativa_id, $director->id,GenerarLlave($accion).'/Comentario', $url);
            auth()->user()->insertNotificacion($titulo, $this->mensajeComentario($jefe->name,$jefe->puesto), now(), $jefe->unidad_administrativa_id, $jefe->id,GenerarLlave($accion).'/Comentario', $url);
            auth()->user()->insertNotificacion($titulo, $this->mensajeComentario($lider->name,$lider->puesto), now(), $lider->unidad_administrativa_id, $lider->id,GenerarLlave($accion).'/Comentario', $url);
            auth()->user()->insertNotificacion($titulo, $this->mensajeComentario($analista->name,$analista->puesto), now(), $analista->unidad_administrativa_id, $analista->id,GenerarLlave($accion).'/Comentario', $url);
            foreach ($staffA as $staff) {
            if (!empty($staff['id'])) {
                $mensaje = '<strong>Estimado(a) '.$staff['name'].', '.$staff['puesto'].':</strong><br>'
                    .'Se registro un comentario por parte del '.auth()->user()->puesto.'; '.auth()->user()->name.', por lo que se debe atender.';  
                auth()->user()->insertNotificacion($titulo, $mensaje, now(), $staff['unidad_administrativa_id'], $staff['id'], GenerarLlave($accion).'/Comentario', $url);
            }
        }
        }
        if(auth()->user()->siglas_rol=='ATUS'){
           auth()->user()->insertNotificacion($titulo, $this->mensajeComentario($jefe->name,$jefe->puesto), now(), $jefe->unidad_administrativa_id, $jefe->id,GenerarLlave($accion).'/Comentario', $url); 
           auth()->user()->insertNotificacion($titulo, $this->mensajeComentario($director->name,$director->puesto), now(), $director->unidad_administrativa_id, $director->id,GenerarLlave($accion).'/Comentario', $url); 
            foreach ($staffA as $staff) {
            if (!empty($staff['id'])) {
                $mensaje = '<strong>Estimado(a) '.$staff['name'].', '.$staff['puesto'].':</strong><br>'
                    .'Se registro un comentario por parte del '.auth()->user()->puesto.'; '.auth()->user()->name.', por lo que se debe atender.';  
                auth()->user()->insertNotificacion($titulo, $mensaje, now(), $staff['unidad_administrativa_id'], $staff['id'], GenerarLlave($accion).'/Comentario', $url);
            }
        }
        }
        if(auth()->user()->siglas_rol=='TUS'){
            auth()->user()->insertNotificacion($titulo, $this->mensajeComentario($director->name,$director->puesto), now(), $director->unidad_administrativa_id, $director->id,GenerarLlave($accion).'/Comentario', $url);
            auth()->user()->insertNotificacion($titulo, $this->mensajeComentario($jefe->name,$jefe->puesto), now(), $jefe->unidad_administrativa_id, $jefe->id,GenerarLlave($accion).'/Comentario', $url);
            auth()->user()->insertNotificacion($titulo, $this->mensajeComentario($lider->name,$lider->puesto), now(), $lider->unidad_administrativa_id, $lider->id,GenerarLlave($accion).'/Comentario', $url);
            auth()->user()->insertNotificacion($titulo, $this->mensajeComentario($analista->name,$analista->puesto), now(), $analista->unidad_administrativa_id, $analista->id,GenerarLlave($accion).'/Comentario', $url);
            foreach ($staffA as $staff) {
                if (!empty($staff['id'])) {
                    $mensaje = '<strong>Estimado(a) '.$staff['name'].', '.$staff['puesto'].':</strong><br>'
                        .'Se registro un comentario por parte del '.auth()->user()->puesto.'; '.auth()->user()->name.', por lo que se debe atender.';  
                    auth()->user()->insertNotificacion($titulo, $mensaje, now(), $staff['unidad_administrativa_id'], $staff['id'], GenerarLlave($accion).'/Comentario', $url);
                }
            }
        }
       elseif(auth()->user()->siglas_rol=='DS'){
            auth()->user()->insertNotificacion($titulo, $this->mensajeComentario($jefe->name,$jefe->puesto), now(), $jefe->unidad_administrativa_id, $jefe->id,GenerarLlave($accion).'/Comentario', $url);
            auth()->user()->insertNotificacion($titulo, $this->mensajeComentario($lider->name,$lider->puesto), now(), $lider->unidad_administrativa_id, $lider->id,GenerarLlave($accion).'/Comentario', $url);
            auth()->user()->insertNotificacion($titulo, $this->mensajeComentario($analista->name,$analista->puesto), now(), $analista->unidad_administrativa_id, $analista->id,GenerarLlave($accion).'/Comentario', $url);
            foreach ($staffA as $staff) {
                if (!empty($staff['id'])) {
                    $mensaje = '<strong>Estimado(a) '.$staff['name'].', '.$staff['puesto'].':</strong><br>'
                        .'Se registro un comentario por parte del '.auth()->user()->puesto.'; '.auth()->user()->name.', por lo que se debe atender.';  
                    auth()->user()->insertNotificacion($titulo, $mensaje, now(), $staff['unidad_administrativa_id'], $staff['id'], GenerarLlave($accion).'/Comentario', $url);
                }
            }
        }
        elseif (auth()->user()->siglas_rol=='JD') {
            auth()->user()->insertNotificacion($titulo, $this->mensajeComentario($lider->name,$lider->puesto), now(), $lider->unidad_administrativa_id, $lider->id,GenerarLlave($accion).'/Comentario', $url);
            auth()->user()->insertNotificacion($titulo, $this->mensajeComentario($analista->name,$analista->puesto), now(), $analista->unidad_administrativa_id, $analista->id,GenerarLlave($accion).'/Comentario', $url);
            foreach ($staffA as $staff) {
                if (!empty($staff['id'])) {
                    $mensaje = '<strong>Estimado(a) '.$staff['name'].', '.$staff['puesto'].':</strong><br>'
                        .'Se registro un comentario por parte del '.auth()->user()->puesto.'; '.auth()->user()->name.', por lo que se debe atender.';  
                    auth()->user()->insertNotificacion($titulo, $mensaje, now(), $staff['unidad_administrativa_id'], $staff['id'], GenerarLlave($accion).'/Comentario', $url);
                }
            }
        } 
        elseif (auth()->user()->siglas_rol=='LP') {
            auth()->user()->insertNotificacion($titulo, $this->mensajeComentario($analista->name,$analista->puesto), now(), $analista->unidad_administrativa_id, $analista->id,GenerarLlave($accion).'/Comentario', $url);
            foreach ($staffA as $staff) {
                if (!empty($staff['id'])) {
                    $mensaje = '<strong>Estimado(a) '.$staff['name'].', '.$staff['puesto'].':</strong><br>'
                        .'Se registro un comentario por parte del '.auth()->user()->puesto.'; '.auth()->user()->name.', por lo que se debe atender.';  
                    auth()->user()->insertNotificacion($titulo, $mensaje, now(), $staff['unidad_administrativa_id'], $staff['id'], GenerarLlave($accion).'/Comentario', $url);
                }
            }        
        }elseif(auth()->user()->siglas_rol=='STAFF'){
            auth()->user()->insertNotificacion($titulo, $this->mensajeComentario($analista->name,$analista->puesto), now(), $analista->unidad_administrativa_id, $analista->id,GenerarLlave($accion).'/Comentario', $url);
            auth()->user()->insertNotificacion($titulo, $this->mensajeComentario($lider->name,$lider->puesto), now(), $lider->unidad_administrativa_id, $lider->id,GenerarLlave($accion).'/Comentario', $url);
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
        return view('comentarios.revisionespliegos.show', compact('comentario'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Revisiones $comentario, Request $request)
    {

        $accion = 'Editar';        
        $acciones=AuditoriaAccion::find(getSession('pliegosobservacionauditoriaaccion_id'));
        $tipo = $comentario->tipo;
        $auditoria = Auditoria::find(getSession('auditoria_id'));
        // dd($tipo);
        setSession('comentario_id',$comentario->id);

        return view('comentarios.revisionespliegos.form', compact('comentario','accion','tipo'));

        // return redirect()->route('revisionespliegosatencion.form',compact('comentario'));
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
        dd(2);
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
        // dd($nombre);
        $mensaje = '<strong>Estimado(a) '.$nombre.', '.$puesto.':</strong><br>'
                    .'Se registro un comentario por parte del '.auth()->user()->puesto.'; '.auth()->user()->name.', por lo que se debe atender.';    
        return $mensaje;
    }
}
