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
        $accion2 = AuditoriaAccion::find(getSession('recomendacionesauditoriaaccion_id'));
        $request->merge([
                'id_revision'=>$comentario->id,
                'de_usuario_id'=>auth()->user()->id,
                'para_usuario_id'=>intval($accion2->analista_asignado_id),
                'accion'=>'Recomendación',
                'accion_id'=>$accion2->id,            
                'usuario_creacion_id'=>auth()->user()->id,
                'tipo'=>$comentario->tipo,
            ]);
        if($comentario->tipo == "Analisis"){
            $request->merge(['muestra_rev'=> $request->analisis]);     
        }elseif($comentario->tipo == "Conclusión"){
            $request->merge(['muestra_rev'=> $request->conclusion]);

        }elseif($comentario->tipo == "Listado Documentos"){
            $request->merge(['muestra_rev'=> $request->listado_documentos]);
        }  

        $request->merge(['comentario'=> $request->respuesta]);
        setMessage('se guardo el comentario correctamente.');      
        
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
        /***************************************************************************************************************************** */
        $recomendacion = Recomendaciones::find(getSession('recomendacioncalificacion_id'));
        $accion=$recomendacion->accion;
        $accion2 = 'Atender';
        $acciones=AuditoriaAccion::find(getSession('recomendacionesauditoriaaccion_id'));

        if(empty($comentario->id_revision)){
            
            setSession('comentarioAsis_id',$comentario->id);
            return redirect()->route('respuestacomentariosrecomendaciones.create');
        }else{
            $respuesta = $comentario;
            //dd($respuesta);
            if(empty($respuesta->tipo)){
                $tipo = $comentario->tipo; // tipo para identificar el ar
            }else{
                $tipo = $respuesta->tipo; // tipo para identificar el ar
            }
            //dd($comentario);
            $comentario = Revisiones::where('id',$comentario->id_revision)->first();
            $AtenderComentario = $respuesta;
            $accion3 = "Editar";
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
    public function update(Revisiones $comentario, Request $request)
    {    
        /************************************************************************************************** */
        //dd($request,$comentario);
        $AtenderComentario = $comentario;
        $respuesta = $comentario;
        $recomendacion = Recomendaciones::find(getSession('recomendacioncalificacion_id'));
        $comentario = Revisiones::where('id',$comentario->id_revision)->first();
        
        //$comentario = Revisiones::find(getSession('comentarioAsis_id'));        
        if($comentario->tipo == "Analisis"){
           $respuesta->update(['muestra_rev'=> $request->analisis]);     
        }elseif($comentario->tipo == "Conclusión"){
            $respuesta->update(['muestra_rev'=> $request->conclusion]);

        }elseif($comentario->tipo == "Listado Documentos"){
            $respuesta->update(['muestra_rev'=> $request->listado_documentos]);
        }
        $respuesta->update(['comentario'=> $request->respuesta]);
        setMessage('se guardo el comentario correctamente.');      
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

    public function enviarcomentario(Revisiones $respuesta){
        $comentario = Revisiones::where('id',$respuesta->id_revision)->first();
        $recomendacion = Recomendaciones::find(getSession('recomendacioncalificacion_id'));
 
        if($comentario->tipo == "Analisis"){
           $recomendacion->update(['analisis'=> $respuesta->muestra_rev]);     
        }elseif($comentario->tipo == "Conclusión"){
            $recomendacion->update(['conclusion'=> $respuesta->muestra_rev]);
        }elseif($comentario->tipo == "Listado Documentos"){
            $recomendacion->update(['listado_documentos'=> $respuesta->muestra_rev]);
        }
        $comentario->update(['estatus'=>'Atendido']);     
        $respuesta->update(['estatus'=>'Atendido']);     
        setMessage('se atendio el comentario correctamente.');      
        return view('layouts.close');
    }
}
