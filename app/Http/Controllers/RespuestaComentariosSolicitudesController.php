<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use App\Models\AuditoriaAccion;
use App\Models\SolicitudesAclaracion;
use App\Models\SolicitudesAclaracionDocumento;
use App\Models\Revisiones;
use Illuminate\Http\Request;

class RespuestaComentariosSolicitudesController extends Controller
{

    public function __construct(SolicitudesAclaracionDocumento $model)
    {       
        $this->model = $model;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //$documento = $this->setQuery($request)->paginate(50);   
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $comentario = Revisiones::find(getSession('comentarioAsis_id'));  
        //dd($comentario);      
        $solicitudes= SolicitudesAclaracion::find(getSession('solicitudesaclaracionatencion_id'));
        $acciones=AuditoriaAccion::find(getSession('solicitudesauditoriaaccion_id'));
        $AtenderComentario = new Revisiones();
        $accion=$solicitudes->accion;
        $accion2 = 'Atender';
        $accion3 = "crear";

        $tipo = $comentario->tipo; // tipo para identificar el ar

        return view('respuestacomentariossolicitudes.form',compact('solicitudes','accion','accion2','comentario','tipo','AtenderComentario','accion3'));
    
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $comentario = Revisiones::find(getSession('comentarioAsis_id'));        
        $solicitudes= SolicitudesAclaracion::find(getSession('solicitudesaclaracionatencion_id'));

        $respuesta = Revisiones::where('id_revision',$comentario->id)->first();
        $accion2 = AuditoriaAccion::find(getSession('solicitudesauditoriaaccion_id'));

        $request->merge([
            'id_revision'=>$comentario->id,
            'de_usuario_id'=>auth()->user()->id,
            'para_usuario_id'=>intval($accion2->analista_asignado_id),
            'accion'=>'Solicitud de Aclaración',
            'accion_id'=>$accion2->id,            
            'usuario_creacion_id'=>auth()->user()->id,
        ]);        

        if($comentario->tipo == "Analisis"){
            $request->merge(['muestra_rev'=> $request->analisis]);     
        }elseif($comentario->tipo == "Conclusión"){
            $request->merge(['muestra_rev'=> $request->conclusion]);

        }elseif($comentario->tipo == "Listado Documentos"){
            $request->merge(['muestra_rev'=> $request->listado_documentos]);
        }  

        if($request->estatus=="Enviar"){
            if($comentario->tipo == "Analisis"){
                $solicitudes->update(['analisis'=> $request->analisis]);     
            }elseif($comentario->tipo == "Conclusión"){
                $solicitudes->update(['conclusion'=> $request->conclusion]);

            }elseif($comentario->tipo == "Listado Documentos"){
                $solicitudes->update(['listado_documentos'=> $request->listado_documentos]);
            } 

            $comentario->update(['estatus'=>'Atendido']);  
            setMessage('se atendio el comentario correctamente.');        
        }else{
            $request->merge(['comentario'=> " "]);
            setMessage('se guardo el comentario correctamente.');      
        }
        Revisiones::create($request->all());  

        return view('layouts.close');
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
    public function edit(Revisiones $comentario,Request $request)
    {
        // dd($request);
        setSession('comentarioAsis_id',$comentario->id);
        $solicitudes= SolicitudesAclaracion::find(getSession('solicitudesaclaracionatencion_id'));
        // dd($pliegos);
        $accion=$solicitudes->accion;
        $accion2 = 'Atender';
        $acciones=AuditoriaAccion::find(getSession('solicitudesauditoriaaccion_id'));
        $tipo = $comentario->tipo; // tipo para identificar el ar
        $respuesta = Revisiones::where('id_revision',$comentario->id)->first();
        $accion3 = "Editar";
        $AtenderComentario = $respuesta;

        //return view('respuestacomentariossolicitudes.form',compact('solicitudes','accion','accion2','comentario','tipo'));
        
        if(empty($respuesta)){
            return redirect()->route('respuestacomentariossolicitudes.create');
        }else{
             return view('respuestacomentariossolicitudes.form',compact('solicitudes','accion','accion2','comentario','tipo','AtenderComentario','accion3'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $comentario = Revisiones::find(getSession('comentarioAsis_id'));
        $solicitudes = SolicitudesAclaracion::find(getSession('solicitudesaclaracionatencion_id'));
        $respuesta = Revisiones::where('id_revision',$comentario->id)->first();

        if($request->estatus=="Enviar"){
            $solicitudes->update($request->all());  
            $comentario->update(['estatus'=>'Atendido']);     
            setMessage('se atendio el comentario correctamente.');      

        }else{
            setMessage('se guardo el comentario correctamente.');      
        }
        if($comentario->tipo == "Analisis"){
           $respuesta->update(['muestra_rev'=> $request->analisis]);     
        }elseif($comentario->tipo == "Conclusión"){
            $respuesta->update(['muestra_rev'=> $request->conclusion]);

        }elseif($comentario->tipo == "Listado Documentos"){
            $respuesta->update(['muestra_rev'=> $request->listado_documentos]);
        }
        $respuesta->update(['comentario'=> $request->comentario]);
        setMessage('se atendio el comentario correctamente.');        
        
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
