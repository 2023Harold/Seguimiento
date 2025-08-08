<?php


namespace App\Http\Controllers\Cedulas;

use App\Http\Controllers\Controller;
use App\Models\Auditoria;
use App\Models\AuditoriaAccion;
use App\Models\Cedula;
use App\Models\Movimientos;
use App\Models\Segpras;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CedulaGeneralPRASController extends Controller
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
        $resultado = $this->generatepdf($auditoria);

        $analistasF =$resultado['analistasF'];
        $analistasL = $resultado['analistasL'];        
        $lideresF = $resultado['lideresF'];
        $lideresL = $resultado['lideresL'];        
        $jefe = $resultado['jefe'];       
        $nombre = $resultado['nombre'];  
		
		return redirect(asset($nombre));        
        return view('cedulageneralpras.form',compact('nombre','auditoria','analistasF','analistasL','lideresF','lideresL','jefesF','jefesL'));
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
        if(count($auditoria->cedulageneralpras)==0){ 
            
            $resultado = $this->generatepdf($auditoria);

            $request['auditoria_id']=$auditoria->id;
            $request['cedula_tipo']='Cedula General PRAS';            
            $request['usuario_creacion_id']=auth()->id();   

            $request['cedula_pras']=$resultado['nombre'];
            mover_archivos($request, ['cedula_pras']);
            $request['cedula']=$request->cedula_pras;


            $cedula=Cedula::create($request->all());            
            $lideres=array_unique($auditoria->acciones->pluck('lider_asignado_id', 'id')->toArray());

            if(count($lideres)==1){                        
                    //AuditoriaAccion::where('analista_asignado_id',auth()->user()->id)->where('segauditoria_id',$auditoria->id)->update(['aprobar_cedini_analista'=>'Si']);
                    if (strlen($cedula->nivel_autorizacion) == 3) {
                        $nivel_autorizacion = $cedula->nivel_autorizacion;
                    } else {
                        $nivel_autorizacion = substr(auth()->user()->unidad_administrativa_id, 0, 4);
                    }
            
                $cedula->update(['fase_autorizacion' =>  'En revisión', 'nivel_autorizacion' => $nivel_autorizacion]);
        
                $titulo = 'Revisión del la Cédula General PRAS de la Auditoría No. '.$auditoria->numero_auditoria;
                $mensaje = '<strong>Estimado (a) ' . auth()->user()->jefe->name . ', ' . auth()->user()->jefe->puesto . ':</strong><br>
                            Ha sido registrada la Cédula General PRAS de la Auditoría No. '.$auditoria->numero_auditoria . ', por parte del ' .
                            auth()->user()->puesto.' '.auth()->user()->name . ', por lo que se requiere realice la revisión.';
    
                auth()->user()->insertNotificacion($titulo, $mensaje, now(), auth()->user()->jefe->unidad_administrativa_id,auth()->user()->jefe->id);
            }else{       
                $lideres=array_unique($auditoria->acciones->pluck('lider_asignado_id', 'id')->toArray());
            
                foreach ($lideres as $lider) {           
                    if(auth()->user()->id != $lider){
                        $usuario=User::find($lider);
    
                        $titulo = 'Revisión del la Cédula General PRAS de la Auditoría No. '.$auditoria->numero_auditoria;
                        $mensaje = '<strong>Estimado (a) ' . $usuario->name . ', ' . $usuario->puesto . ':</strong><br>
                                    Ha sido registrada la Cédula General PRAS de la Auditoría No. '.$auditoria->numero_auditoria . ', por parte del ' .
                                    auth()->user()->puesto.' '.auth()->user()->name . ', por lo que se requiere realice la revisión.';
    
                        auth()->user()->insertNotificacion($titulo, $mensaje, now(), $usuario->unidad_administrativa_id,$usuario->id);
                    }          
                }
            }
                        
            AuditoriaAccion::where('lider_asignado_id',auth()->user()->id)->where('segauditoria_id',$auditoria->id)->update(['aprobar_cedpras_lider'=>'Si']);
            
            Movimientos::create([
                'tipo_movimiento' => 'Registro de la Cédula General PRAS',
                'accion' => 'Cédula General PRAS',
                'accion_id' => $auditoria->id,
                'estatus' => 'Aprobado',
                'usuario_creacion_id' => auth()->id(),
                'usuario_asignado_id' => auth()->id(),
            ]);

        }else{    
                
            $cedula=$auditoria->cedulageneralpras[0];             
            $lideres=array_unique($auditoria->acciones->pluck('lider_asignado_id', 'id')->toArray());

            if(count($lideres)==1){
                if (strlen($cedula->nivel_autorizacion) == 3) {
                    $nivel_autorizacion = $cedula->nivel_autorizacion;
                } else {
                    $nivel_autorizacion = substr(auth()->user()->unidad_administrativa_id, 0, 4);
                }
        
                $cedula->update(['fase_autorizacion' =>  'En revisión 01', 'nivel_autorizacion' => $nivel_autorizacion]);
        
                $titulo = 'Revisión del la Cédula General PRAS de la Auditoría No. '.$auditoria->numero_auditoria;
                $mensaje = '<strong>Estimado (a) ' . auth()->user()->jefe->name . ', ' . auth()->user()->jefe->puesto . ':</strong><br>
                            Ha sido registrada la Cédula General PRAS de la Auditoría No. '.$auditoria->numero_auditoria . ', por parte del ' .
                            auth()->user()->puesto.' '.auth()->user()->name . ', por lo que se requiere realice la revisión.';

                auth()->user()->insertNotificacion($titulo, $mensaje, now(), auth()->user()->jefe->unidad_administrativa_id,auth()->user()->jefe->id);
                
            }else{
             
                if (strlen($cedula->nivel_autorizacion) == 3) {
                    $nivel_autorizacion = $cedula->nivel_autorizacion;
                } else {
                    $nivel_autorizacion = substr(auth()->user()->unidad_administrativa_id, 0, 4);
                }
               
                foreach ($lideres as $lider) {           
                    if(auth()->user()->id != $lider){
                        $usuario=User::find($lider);
    
                        $titulo = 'Revisión del la Cédula General PRAS de la Auditoría No. '.$auditoria->numero_auditoria;
                        $mensaje = '<strong>Estimado (a) ' . $usuario->name . ', ' . $usuario->puesto . ':</strong><br>
                                    Ha sido registrada la Cédula General PRAS de la Auditoría No. '.$auditoria->numero_auditoria . ', por parte del ' .
                                    auth()->user()->puesto.' '.auth()->user()->name . ', por lo que se requiere realice la revisión.';
    
                        auth()->user()->insertNotificacion($titulo, $mensaje, now(), $usuario->unidad_administrativa_id,$usuario->id);
                    }          
                }

                AuditoriaAccion::where('lider_asignado_id',auth()->user()->id)->where('segauditoria_id',$auditoria->id)->update(['aprobar_cedpras_lider'=>'Si']);
                $cedula->update(['fase_autorizacion' =>  null, 'nivel_autorizacion' => null]);     
            }
                      
            AuditoriaAccion::where('lider_asignado_id',auth()->user()->id)->where('segauditoria_id',$auditoria->id)->update(['aprobar_cedpras_lider'=>'Si']);
            
            Movimientos::create([
                'tipo_movimiento' => 'Registro de la Cédula General PRAS',
                'accion' => 'Cédula General PRAS',
                'accion_id' => $auditoria->id,
                'estatus' => 'Aprobado',
                'usuario_creacion_id' => auth()->id(),
                'usuario_asignado_id' => auth()->id(),

            ]);
        }
    
        setMessage('Se ha iniciado el proceso de revisión para la Cédula General PRAS.');
       
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

        $accionesanalistasFaltantes=AuditoriaAccion::whereNull('aprobar_cedpras_analista')->where('segauditoria_id',$auditoria->id)->get();      
        $accionesanalistasListos=AuditoriaAccion::whereNotNull('aprobar_cedpras_analista')->where('segauditoria_id',$auditoria->id)->get();  
        $accionesLideresFaltantes=AuditoriaAccion::whereNull('aprobar_cedpras_lider')->where('segauditoria_id',$auditoria->id)->get();      
        $accionesLideresListos=AuditoriaAccion::whereNotNull('aprobar_cedpras_lider')->where('segauditoria_id',$auditoria->id)->get();  
        $accionesJefesFaltantes=AuditoriaAccion::whereNull('aprobar_cedpras_jefe')->where('segauditoria_id',$auditoria->id)->get();      
        $accionesJefesListos=AuditoriaAccion::whereNotNull('aprobar_cedpras_jefe')->where('segauditoria_id',$auditoria->id)->get();       
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
			/*
            $jefesL=array_unique($accionesJefesListos->pluck('departamento_asignado_id', 'id')->toArray());
            $nombresJefesL=[];

            if(count($jefesL)>0){            
                foreach($jefesL as $jefeid){
                    $jefe=User::where('unidad_administrativa_id',$jefeid)->first();
                    $nombresJefesL[$jefe->id] = $jefe->name;
                }
            }*/
            
            $fechaminima=Segpras::where('auditoria_id',$auditoria->id)->min('fecha_acuse_oficio');
            $fechainicio = Carbon::parse($fechaminima); 
            $fechamaxima=Segpras::where('auditoria_id',$auditoria->id)->max('fecha_proxima_seguimiento');
            $fechavencimiento = Carbon::parse($fechamaxima); 

                
            $pdf = Pdf::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])
			->loadView('cedulageneralpras.show',compact('auditoria','fechainicio','fechavencimiento','director','nombresanalistasL','nombreslideresL','jefe'))
			->setPaper('a4', 'landscape')->stream('archivo.pdf');
            $nombre='storage/temporales/CedulaGeneralPRAS'.str_replace("/", "_", $auditoria->numero_auditoria).'.pdf';
            $pdfgenrado = file_put_contents($nombre, $pdf);		
        
/*
        $fechaminima=Segpras::where('auditoria_id',$auditoria->id)->min('fecha_acuse_oficio');
        $fechainicio = Carbon::parse($fechaminima); 
        $fechamaxima=Segpras::where('auditoria_id',$auditoria->id)->max('fecha_proxima_seguimiento');
        $fechavencimiento = Carbon::parse($fechamaxima); 

        if(count($auditoria->cedulageneralpras)==0){           
            $pdf = Pdf::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadView('cedulageneralpras.show',compact('auditoria','fechainicio','fechavencimiento'))->setPaper('a4', 'landscape')->stream('archivo.pdf');
            $nombre='storage/temporales/CedulaGeneralPRAS'.str_replace("/", "_", $auditoria->numero_auditoria).'.pdf';
            $pdfgenrado = file_put_contents($nombre, $pdf);
            
        }else{
            $nombre=$auditoria->cedulageneralpras[0]->cedula; 
        }*/

        $resultado=[
            'nombre'=>$nombre,            
            'analistasF'=>$analistasF,
            'analistasL'=>$analistasL,
            'lideresF'=>$lideresF,
            'lideresL'=>$lideresL,
            //'jefesF'=>$jefesF,
            //'jefesL'=>$jefesL,
			'jefe'=>$jefe,
        ];

        return $resultado;
    }
}
