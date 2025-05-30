<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use App\Models\AuditoriaAccion;
use App\Models\Cedula;
use App\Models\Movimientos;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class CedulaAnaliticaDesempenoController extends Controller
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
        
        $resultado=$this->generatepdf($auditoria);

        $analistasF =$resultado['analistasF'];
        $analistasL = $resultado['analistasL'];        
        $lideresF = $resultado['lideresF'];
        $lideresL = $resultado['lideresL'];        
		$jefe = $resultado['jefe'];  
        $nombre = $resultado['nombre'];
		
		return redirect(asset($nombre));                 
        //return view('cedulaanaliticadesempeno.index',compact('nombre','auditoria','analistasF','analistasL','lideresF','lideresL','jefesF','jefesL'));
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
        if(count($auditoria->cedulaanaliticadesemp)==0){  
            $resultado = $this->generatepdf($auditoria);         
            $request['auditoria_id']=$auditoria->id;
            $request['cedula_tipo']='Cedula Analítica Desempeño';            
            $request['usuario_creacion_id']=auth()->id();   

            $request['cedula_analitica_des']=$resultado['nombre'];
            mover_archivos($request, ['cedula_analitica_des']);
            $request['cedula']=$request->cedula_analitica_des;


            $cedula=Cedula::create($request->all());            
            $analistas=array_unique($auditoria->acciones->pluck('analista_asignado_id', 'id')->toArray());

            if(count($analistas)==1){                        
                    //AuditoriaAccion::where('analista_asignado_id',auth()->user()->id)->where('segauditoria_id',$auditoria->id)->update(['aprobar_cedini_analista'=>'Si']);
                    if (strlen($cedula->nivel_autorizacion) == 3) {
                        $nivel_autorizacion = $cedula->nivel_autorizacion;
                    } else {
                        $nivel_autorizacion = substr(auth()->user()->unidad_administrativa_id, 0, 4);
                    }
            
                $cedula->update(['fase_autorizacion' =>  'En revisión 01', 'nivel_autorizacion' => $nivel_autorizacion]);
        
                $titulo = 'Revisión del la Cédula Analítica de Desempeño de la Auditoría No. '.$auditoria->numero_auditoria;
                $mensaje = '<strong>Estimado (a) ' . auth()->user()->jefe->name . ', ' . auth()->user()->jefe->puesto . ':</strong><br>
                            Ha sido registrada la Cédula Analítica de Desempeño de la Auditoría No. '.$auditoria->numero_auditoria . ', por parte del ' .
                            auth()->user()->puesto.' '.auth()->user()->name . ', por lo que se requiere realice la revisión.';
    
                auth()->user()->insertNotificacion($titulo, $mensaje, now(), auth()->user()->jefe->unidad_administrativa_id,auth()->user()->jefe->id);
            }else{       
                $analistas=array_unique($auditoria->acciones->pluck('analista_asignado_id', 'id')->toArray());
            
                foreach ($analistas as $analista) {           
                    if(auth()->user()->id != $analista){
                        $usuario=User::find($analista);
    
                        $titulo = 'Revisión del la Cédula Analítica de Desempeño de la Auditoría No. '.$auditoria->numero_auditoria;
                        $mensaje = '<strong>Estimado (a) ' . $usuario->name . ', ' . $usuario->puesto . ':</strong><br>
                                    Ha sido registrada la Cédula Analítica de Desempeño de la Auditoría No. '.$auditoria->numero_auditoria . ', por parte del ' .
                                    auth()->user()->puesto.' '.auth()->user()->name . ', por lo que se requiere realice la revisión.';
    
                        auth()->user()->insertNotificacion($titulo, $mensaje, now(), $usuario->unidad_administrativa_id,$usuario->id);
                    }          
                }
            }
                        
            AuditoriaAccion::where('analista_asignado_id',auth()->user()->id)->where('segauditoria_id',$auditoria->id)->update(['aprobar_cedanades_analista'=>'Si']);
            
            Movimientos::create([
                'tipo_movimiento' => 'Registro de la Cédula Analítica de Desempeño',
                'accion' => 'Cédula Analítica Desempeño',
                'accion_id' => $auditoria->id,
                'estatus' => 'Aprobado',
                'usuario_creacion_id' => auth()->id(),
                'usuario_asignado_id' => auth()->id(),
            ]);

        }else{    
                
            $cedula=$auditoria->cedulaanaliticadesemp[0];             
            $analistas=array_unique($auditoria->acciones->pluck('analista_asignado_id', 'id')->toArray());

            if(count($analistas)==1){
                if (strlen($cedula->nivel_autorizacion) == 3) {
                    $nivel_autorizacion = $cedula->nivel_autorizacion;
                } else {
                    $nivel_autorizacion = substr(auth()->user()->unidad_administrativa_id, 0, 4);
                }
        
                $cedula->update(['fase_autorizacion' =>  'En revisión 01', 'nivel_autorizacion' => $nivel_autorizacion]);
        
                $titulo = 'Revisión del la Cédula Analítica de Desempeño de la Auditoría No. '.$auditoria->numero_auditoria;
                $mensaje = '<strong>Estimado (a) ' . auth()->user()->jefe->name . ', ' . auth()->user()->jefe->puesto . ':</strong><br>
                            Ha sido registrada la Cédula Analítica de Desempeño de la Auditoría No. '.$auditoria->numero_auditoria . ', por parte del ' .
                            auth()->user()->puesto.' '.auth()->user()->name . ', por lo que se requiere realice la revisión.';

                auth()->user()->insertNotificacion($titulo, $mensaje, now(), auth()->user()->jefe->unidad_administrativa_id,auth()->user()->jefe->id);
                
            }else{
             
                if (strlen($cedula->nivel_autorizacion) == 3) {
                    $nivel_autorizacion = $cedula->nivel_autorizacion;
                } else {
                    $nivel_autorizacion = substr(auth()->user()->unidad_administrativa_id, 0, 4);
                }
               
                foreach ($analistas as $analista) {           
                    if(auth()->user()->id != $analista){
                        $usuario=User::find($analista);
    
                        $titulo = 'Revisión del la Cédula Analítica de Desempeño de la Auditoría No. '.$auditoria->numero_auditoria;
                        $mensaje = '<strong>Estimado (a) ' . $usuario->name . ', ' . $usuario->puesto . ':</strong><br>
                                    Ha sido registrada la Cédula Analítica de Desempeño de la Auditoría No. '.$auditoria->numero_auditoria . ', por parte del ' .
                                    auth()->user()->puesto.' '.auth()->user()->name . ', por lo que se requiere realice la revisión.';
    
                        auth()->user()->insertNotificacion($titulo, $mensaje, now(), $usuario->unidad_administrativa_id,$usuario->id);
                    }          
                }

                AuditoriaAccion::where('analista_asignado_id',auth()->user()->id)->where('segauditoria_id',$auditoria->id)->update(['aprobar_cedanades_analista'=>'Si']);
                $cedula->update(['fase_autorizacion' =>  null, 'nivel_autorizacion' => null]);     
            }
                      
            
            Movimientos::create([
                'tipo_movimiento' => 'Registro de la Cédula Analítica de Desempeño',
                'accion' => 'Cédula Analítica Desempeño',
                'accion_id' => $auditoria->id,
                'estatus' => 'Aprobado',
                'usuario_creacion_id' => auth()->id(),
                'usuario_asignado_id' => auth()->id(),

            ]);
        }

        setMessage('Se ha iniciado el proceso de revisión para la Cédula Analítica de Desempeño.');
       
        return redirect()->route('cedulainicial.index');
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

    public function generatepdf(Auditoria $auditoria){

        $accionesanalistasFaltantes=AuditoriaAccion::whereNull('aprobar_cedanades_analista')->where('segauditoria_id',$auditoria->id)->get();      
        $accionesanalistasListos=AuditoriaAccion::whereNotNull('aprobar_cedanades_analista')->where('segauditoria_id',$auditoria->id)->get();  
        $accionesLideresFaltantes=AuditoriaAccion::whereNull('aprobar_cedanades_lider')->where('segauditoria_id',$auditoria->id)->get();      
        $accionesLideresListos=AuditoriaAccion::whereNotNull('aprobar_cedanades_lider')->where('segauditoria_id',$auditoria->id)->get();  
        $accionesJefesFaltantes=AuditoriaAccion::whereNull('aprobar_cedanades_jefe')->where('segauditoria_id',$auditoria->id)->get();      
        $accionesJefesListos=AuditoriaAccion::whereNotNull('aprobar_cedanades_jefe')->where('segauditoria_id',$auditoria->id)->get();       
        $analistasF=array_unique($accionesanalistasFaltantes->pluck('analista_asignado_id', 'id')->toArray());
        $analistasL=array_unique($accionesanalistasListos->pluck('analista_asignado_id', 'id')->toArray());        
        $lideresF=array_unique($accionesLideresFaltantes->pluck('lider_asignado_id', 'id')->toArray());
        $lideresL=array_unique($accionesLideresListos->pluck('lider_asignado_id', 'id')->toArray());        
        $jefesF=array_unique($accionesJefesFaltantes->pluck('departamento_asignado_id', 'id')->toArray());
        $jefesL=array_unique($accionesJefesListos->pluck('departamento_asignado_id', 'id')->toArray());        
        
		//$director=User::where('unidad_administrativa_id',substr($cedula->userCreacion->unidad_administrativa_id, 0, 4).'00')->where('siglas_rol','DS')->first();
        //$jefe=User::where('unidad_administrativa_id',substr($cedula->userCreacion->unidad_administrativa_id, 0, 5).'0')->where('siglas_rol','JD')->first();
		$director=$auditoria->directorasignado;
        $jefe=$auditoria->jefedepartamentoencargado;
               
            $accionesanalistasListos=AuditoriaAccion::where('segauditoria_id',$auditoria->id)->get(); 
            $accionesLideresListos=AuditoriaAccion::where('segauditoria_id',$auditoria->id)->get();  
            //$accionesJefesListos=AuditoriaAccion::where('segauditoria_id',$auditoria->id)->get();    
            $analistasL=array_unique($accionesanalistasListos->pluck('analista_asignado_id', 'id')->toArray());
            $nombresanalistasL=array_unique($accionesanalistasListos->pluck('analista_asignado', 'id')->toArray());
            $lideresL=array_unique($accionesLideresListos->pluck('lider_asignado_id', 'id')->toArray());
            $nombreslideresL=array_unique($accionesLideresListos->pluck('lider_asignado', 'id')->toArray());
            /*$jefesL=array_unique($accionesJefesListos->pluck('departamento_asignado_id', 'id')->toArray());
            $nombresJefesL=[];

            if(count($jefesL)>0){            
                foreach($jefesL as $jefeid){
                    $jefe=User::where('unidad_administrativa_id',$jefeid)->first();
                    $nombresJefesL[$jefe->id] = $jefe->name;
                }
            } */
                
            $pdf = Pdf::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])
			->loadView('cedulaanaliticadesempeno.show',compact('auditoria','director','nombresanalistasL','nombreslideresL','jefe'))->setPaper('a4', 'landscape')->stream('archivo.pdf');
            $nombre='storage/temporales/CedulaAnaliticaDes'.str_replace("/", "_", $auditoria->numero_auditoria).'.pdf';
            $pdfgenrado = file_put_contents($nombre, $pdf);     

      /*
        if(count($auditoria->cedulaanaliticadesemp)==0){              
            $pdf = Pdf::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadView('cedulaanaliticadesempeno.show',compact('auditoria'))->setPaper('a4', 'landscape')->stream('archivo.pdf');
            $nombre='storage/temporales/CedulaAnaliticaDes'.str_replace("/", "_", $auditoria->numero_auditoria).'.pdf';
            $pdfgenrado = file_put_contents($nombre, $pdf);           
        }else{
            $nombre=$auditoria->cedulaanaliticadesemp[0]->cedula; 
        }*/

        $resultado=[
            'nombre'=>$nombre,            
            'analistasF'=>$analistasF,
            'analistasL'=>$analistasL,
            'lideresF'=>$lideresF,
            'lideresL'=>$lideresL,
            'jefe'=>$jefe,
        ];

        return $resultado;
    }
}
