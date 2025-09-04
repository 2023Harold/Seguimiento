<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use App\Models\AuditoriaAccion;
use App\Models\Recomendaciones;
use App\Models\RecomendacionesDocumento;
use App\Models\Revisiones;
use Illuminate\Http\Request;

class RespuestaComentariosRecomendacionesController extends Controller
{

    public function __construct(RecomendacionesDocumento $model)
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

     $documento = $this->setQuery($request)->paginate(50);   

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $comentario = Revisiones::find(getSession('comentarioAsis_id'));        
        $recomendacion = Recomendaciones::find(getSession('recomendacioncalificacion_id'));
        $acciones=AuditoriaAccion::find(getSession('recomendacionesauditoriaaccion_id'));
        $AtenderComentario = new Revisiones();
        $accion=$recomendacion->accion;
        $accion2 = 'Atender';
        $accion3 = "crear";

        $tipo = $comentario->tipo; // tipo para identificar el ar

        return view('respuestacomentarios.form',compact('recomendacion','accion','accion2','comentario','tipo','AtenderComentario','accion3'));
    
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
        $recomendacion = Recomendaciones::find(getSession('recomendacioncalificacion_id'));

        $respuesta = Revisiones::where('id_revision',$comentario->id)->first();
        $accion2 = AuditoriaAccion::find(getSession('recomendacionesauditoriaaccion_id'));

        //dd($request,$recomendacion);
        $request->merge([
            'id_revision'=>$comentario->id,
            'de_usuario_id'=>auth()->user()->id,
            'para_usuario_id'=>intval($accion2->analista_asignado_id),
            'accion'=>'Recomendación',
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
                $recomendacion->update(['analisis'=> $request->analisis]);     
            }elseif($comentario->tipo == "Conclusión"){
                $recomendacion->update(['conclusion'=> $request->conclusion]);

            }elseif($comentario->tipo == "Listado Documentos"){
                $recomendacion->update(['listado_documentos'=> $request->listado_documentos]);
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
    public function edit(Recomendaciones $documento,Revisiones $comentario,Request $request)
    {
        // dd($comentario);
        setSession('comentarioAsis_id',$comentario->id);
        $recomendacion = Recomendaciones::find(getSession('recomendacioncalificacion_id'));
        $accion=$recomendacion->accion;
        $accion2 = 'Atender';
        // dd($accion);
        $acciones=AuditoriaAccion::find(getSession('recomendacionesauditoriaaccion_id'));
        $tipo = $comentario->tipo; // tipo para identificar el ar
        $respuesta = Revisiones::where('id_revision',$comentario->id)->first();
        $accion3 = "Editar";
        $AtenderComentario = $respuesta;

        if(empty($respuesta)){
            return redirect()->route('respuestacomentariosrecomendaciones.create');
        }else{
            return view('respuestacomentarios.form',compact('recomendacion','accion','accion2','comentario','tipo','AtenderComentario','accion3'));
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
        $recomendacion = Recomendaciones::find(getSession('recomendacioncalificacion_id'));
        $respuesta = Revisiones::where('id_revision',$comentario->id)->first();

        if($request->estatus=="Enviar"){
            $recomendacion->update($request->all());  
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
