<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use App\Models\AuditoriaAccion;
use App\Models\Cedula;
use App\Models\Movimientos;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class CedulaInicialPrimeraEtapaController extends Controller
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
        $resultado=$this->pdfGenerator($auditoria);
        // $accionesanalistasFaltantes=AuditoriaAccion::whereNull('aprobar_cedini_analista')->where('segauditoria_id',$auditoria->id)->get();      
        // $accionesanalistasListos=AuditoriaAccion::whereNotNull('aprobar_cedini_analista')->where('segauditoria_id',$auditoria->id)->get();  
        // $accionesLideresFaltantes=AuditoriaAccion::whereNull('aprobar_cedini_lider')->where('segauditoria_id',$auditoria->id)->get();      
        // $accionesLideresListos=AuditoriaAccion::whereNotNull('aprobar_cedini_lider')->where('segauditoria_id',$auditoria->id)->get();  
        // $accionesJefesFaltantes=AuditoriaAccion::whereNull('aprobar_cedini_jefe')->where('segauditoria_id',$auditoria->id)->get();      
        // $accionesJefesListos=AuditoriaAccion::whereNotNull('aprobar_cedini_jefe')->where('segauditoria_id',$auditoria->id)->get();       
        // $analistasF=array_unique($accionesanalistasFaltantes->pluck('analista_asignado_id', 'id')->toArray());
        // $analistasL=array_unique($accionesanalistasListos->pluck('analista_asignado_id', 'id')->toArray());        
        // $lideresF=array_unique($accionesLideresFaltantes->pluck('lider_asignado_id', 'id')->toArray());
        // $lideresL=array_unique($accionesLideresListos->pluck('lider_asignado_id', 'id')->toArray());        
        // $jefesF=array_unique($accionesJefesFaltantes->pluck('departamento_asignado_id', 'id')->toArray());
        // $jefesL=array_unique($accionesJefesListos->pluck('departamento_asignado_id', 'id')->toArray());        
        
        
        //  if(count($auditoria->cedulageneralseguimiento)==0){
        //     $TSP=0;
        //     $TSPS=0;
        //     $TSPNS=0;
    
        //     foreach ($auditoria->totalsolacl as $solicitud) {
        //         $TSP= $TSP  +  $solicitud->monto_aclarar;
        //         $TSPS=  $TSPS  +  ((!empty($solicitud->solicitudesaclaracion)&&!empty($solicitud->solicitudesaclaracion->monto_solventado)) ? $solicitud->solicitudesaclaracion->monto_solventado:0);
        //         $TSPNS= $TSPNS + ($solicitud->monto_aclarar - ((!empty($solicitud->solicitudesaclaracion)&& !empty($solicitud->solicitudesaclaracion->monto_solventado))? $solicitud->solicitudesaclaracion->monto_solventado:0));
        //     }
    
        //     //dd($totalSolicitudesPromovidas,$totalSolicitudesPromovidasSolventadas,$totalSolicitudesPromovidasNoSolventadas);
        //     $TPP=0;
        //     $TPPS=0;
        //     $TPPNS=0;
    
        //     foreach ($auditoria->totalpliegos as $pliego) {
        //         $TPP=$TPP+$pliego->monto_aclarar;
        //         $TPPS=$TPPS+((!empty($pliego->pliegosobservacion)&&!empty($pliego->pliegosobservacion->monto_solventado))?$pliego->pliegosobservacion->monto_solventado:0);
        //         $TPPNS=$TPPNS+($pliego->monto_aclarar-((!empty($pliego->pliegosobservacion)&&!empty($pliego->pliegosobservacion->monto_solventado))?$pliego->pliegosobservacion->monto_solventado:0));
        //     }
    
        //     $TAP=$TSP+$TPP;
        //     $TAPS=$TSPS+$TPPS;
        //     $TAPNS=$TSPNS+$TPPNS;
           

           
        //     $pdf = Pdf::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadView('cedulageneral.show',compact('auditoria','TAP','TAPS','TAPNS','TSP','TSPS','TSPNS','TPP','TPPS','TPPNS'))->setPaper('a4', 'landscape')->stream('archivo.pdf');
        //     $nombre='storage/temporales/CedulaGeneral'.str_replace("/", "_", $auditoria->numero_auditoria).'.pdf';
        //     $pdfgenrado = file_put_contents($nombre, $pdf);

           
        // }else{            
        //      $nombre=$auditoria->cedulageneralseguimiento[0]->cedula;           
        // }
       
        $nombre=$resultado['nombre'];
        $analistasF=$resultado['analistasF'];
        $analistasL=$resultado['analistasL'];
        $lideresF=$resultado['lideresF'];
        $lideresL=$resultado['lideresL'];
        $jefesF=$resultado['jefesF'];
        $jefesL=$resultado['jefesL'];
        
        
        return view('cedulageneral.index',compact('nombre','auditoria','analistasF','analistasL','lideresF','lideresL','jefesF','jefesL'));
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
        

        if(count($auditoria->cedulageneralseguimiento)==0){
            $resultado=$this->pdfGenerator($auditoria);
            $nombre=$resultado['nombre'];

            $request['auditoria_id']=$auditoria->id;
            $request['cedula_tipo']='Cedula General Seguimiento';
            
            $request['usuario_creacion_id']=auth()->id();        
            $request['cedula']=$nombre;

            mover_archivos($request, ['cedula']);
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
        
                $titulo = 'Revisión del la Cédula General de Seguimiento de la Auditoría No. '.$auditoria->numero_auditoria;
                $mensaje = '<strong>Estimado (a) ' . auth()->user()->jefe->name . ', ' . auth()->user()->jefe->puesto . ':</strong><br>
                            Ha sido registrada la Cédula General de Seguimiento de la Auditoría No. '.$auditoria->numero_auditoria . ', por parte del ' .
                            auth()->user()->puesto.' '.auth()->user()->name . ', por lo que se requiere realice la revisión.';
    
                auth()->user()->insertNotificacion($titulo, $mensaje, now(), auth()->user()->jefe->unidad_administrativa_id,auth()->user()->jefe->id);

            }else{
                $analistas=array_unique($auditoria->acciones->pluck('analista_asignado_id', 'id')->toArray());
            
                foreach ($analistas as $analista) {           
                    if(auth()->user()->id != $analista){
                        $usuario=User::find($analista);
    
                        $titulo = 'Revisión del la Cédula General de Seguimiento de la Auditoría No. '.$auditoria->numero_auditoria;
                        $mensaje = '<strong>Estimado (a) ' . $usuario->name . ', ' . $usuario->puesto . ':</strong><br>
                                    Ha sido registrada la Cédula General de Seguimiento de la Auditoría No. '.$auditoria->numero_auditoria . ', por parte del ' .
                                    auth()->user()->puesto.' '.auth()->user()->name . ', por lo que se requiere realice la revisión.';
    
                        auth()->user()->insertNotificacion($titulo, $mensaje, now(), $usuario->unidad_administrativa_id,$usuario->id);
                    }          
                }
            }
            
            
            AuditoriaAccion::where('analista_asignado_id',auth()->user()->id)->where('segauditoria_id',$auditoria->id)->update(['aprobar_cedini_analista'=>'Si']);

            
            Movimientos::create([
                'tipo_movimiento' => 'Registro de la Cédula General de Seguimiento',
                'accion' => 'Cédula General de Seguimiento',
                'accion_id' => $auditoria->id,
                'estatus' => 'Aprobado',
                'usuario_creacion_id' => auth()->id(),
                'usuario_asignado_id' => auth()->id(),

            ]);

        }else{
            $cedula=$auditoria->cedulageneralseguimiento[0];

            $analistas=array_unique($auditoria->acciones->pluck('analista_asignado_id', 'id')->toArray());

            if(count($analistas)==1){
                if (strlen($cedula->nivel_autorizacion) == 3) {
                    $nivel_autorizacion = $cedula->nivel_autorizacion;
                } else {
                    $nivel_autorizacion = substr(auth()->user()->unidad_administrativa_id, 0, 4);
                }
        
                $cedula->update(['fase_autorizacion' =>  'En revisión 01', 'nivel_autorizacion' => $nivel_autorizacion]);
        
                $titulo = 'Revisión del la Cédula General de Seguimiento de la Auditoría No. '.$auditoria->numero_auditoria;
                $mensaje = '<strong>Estimado (a) ' . auth()->user()->jefe->name . ', ' . auth()->user()->jefe->puesto . ':</strong><br>
                            Ha sido registrada la Cédula General de Seguimiento de la Auditoría No. '.$auditoria->numero_auditoria . ', por parte del ' .
                            auth()->user()->puesto.' '.auth()->user()->name . ', por lo que se requiere realice la revisión.';

                auth()->user()->insertNotificacion($titulo, $mensaje, now(), auth()->user()->jefe->unidad_administrativa_id,auth()->user()->jefe->id);
                
            }else{
                foreach ($analistas as $analista) {           
                    if(auth()->user()->id != $analista){
                        $usuario=User::find($analista);
    
                        $titulo = 'Revisión del la Cédula General de Seguimiento de la Auditoría No. '.$auditoria->numero_auditoria;
                        $mensaje = '<strong>Estimado (a) ' . $usuario->name . ', ' . $usuario->puesto . ':</strong><br>
                                    Ha sido registrada la Cédula General de Seguimiento de la Auditoría No. '.$auditoria->numero_auditoria . ', por parte del ' .
                                    auth()->user()->puesto.' '.auth()->user()->name . ', por lo que se requiere realice la revisión.';
    
                        auth()->user()->insertNotificacion($titulo, $mensaje, now(), $usuario->unidad_administrativa_id,$usuario->id);
                    }          
                }

                AuditoriaAccion::where('analista_asignado_id',auth()->user()->id)->where('segauditoria_id',$auditoria->id)->update(['aprobar_cedini_analista'=>'Si']);
                $cedula->update(['fase_autorizacion' => null]);
            }
                      
            
            Movimientos::create([
                'tipo_movimiento' => 'Registro de la Cédula General de Seguimiento',
                'accion' => 'Cédula General de Seguimiento',
                'accion_id' => $auditoria->id,
                'estatus' => 'Aprobado',
                'usuario_creacion_id' => auth()->id(),
                'usuario_asignado_id' => auth()->id(),

            ]);
        }

      //$lider=array_unique($auditoria->acciones->pluck('lider_asignado_id', 'id')->toArray());
      //$departamentos=array_unique($auditoria->acciones->pluck('departamento_asignado_id', 'id')->toArray());

      //dd($analistas,$lider,$departamentos);

        setMessage('Se ha iniciado el proceso de revisión para la cedula general de seguimiento.');
       
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

    public function pdfGenerator(Auditoria $auditoria){
        $accionesanalistasFaltantes=AuditoriaAccion::whereNull('aprobar_cedini_analista')->where('segauditoria_id',$auditoria->id)->get();      
        $accionesanalistasListos=AuditoriaAccion::whereNotNull('aprobar_cedini_analista')->where('segauditoria_id',$auditoria->id)->get();  
        $accionesLideresFaltantes=AuditoriaAccion::whereNull('aprobar_cedini_lider')->where('segauditoria_id',$auditoria->id)->get();      
        $accionesLideresListos=AuditoriaAccion::whereNotNull('aprobar_cedini_lider')->where('segauditoria_id',$auditoria->id)->get();  
        $accionesJefesFaltantes=AuditoriaAccion::whereNull('aprobar_cedini_jefe')->where('segauditoria_id',$auditoria->id)->get();      
        $accionesJefesListos=AuditoriaAccion::whereNotNull('aprobar_cedini_jefe')->where('segauditoria_id',$auditoria->id)->get();       
        $analistasF=array_unique($accionesanalistasFaltantes->pluck('analista_asignado_id', 'id')->toArray());
        $analistasL=array_unique($accionesanalistasListos->pluck('analista_asignado_id', 'id')->toArray());        
        $lideresF=array_unique($accionesLideresFaltantes->pluck('lider_asignado_id', 'id')->toArray());
        $lideresL=array_unique($accionesLideresListos->pluck('lider_asignado_id', 'id')->toArray());        
        $jefesF=array_unique($accionesJefesFaltantes->pluck('departamento_asignado_id', 'id')->toArray());
        $jefesL=array_unique($accionesJefesListos->pluck('departamento_asignado_id', 'id')->toArray());        
        
        
         if(count($auditoria->cedulageneralseguimiento)==0){
            $TSP=0;
            $TSPS=0;
            $TSPNS=0;
    
            foreach ($auditoria->totalsolacl as $solicitud) {
                $TSP= $TSP  +  $solicitud->monto_aclarar;
                $TSPS=  $TSPS  +  ((!empty($solicitud->solicitudesaclaracion)&&!empty($solicitud->solicitudesaclaracion->monto_solventado)) ? $solicitud->solicitudesaclaracion->monto_solventado:0);
                $TSPNS= $TSPNS + ($solicitud->monto_aclarar - ((!empty($solicitud->solicitudesaclaracion)&& !empty($solicitud->solicitudesaclaracion->monto_solventado))? $solicitud->solicitudesaclaracion->monto_solventado:0));
            }
    
            //dd($totalSolicitudesPromovidas,$totalSolicitudesPromovidasSolventadas,$totalSolicitudesPromovidasNoSolventadas);
            $TPP=0;
            $TPPS=0;
            $TPPNS=0;
    
            foreach ($auditoria->totalpliegos as $pliego) {
                $TPP=$TPP+$pliego->monto_aclarar;
                $TPPS=$TPPS+((!empty($pliego->pliegosobservacion)&&!empty($pliego->pliegosobservacion->monto_solventado))?$pliego->pliegosobservacion->monto_solventado:0);
                $TPPNS=$TPPNS+($pliego->monto_aclarar-((!empty($pliego->pliegosobservacion)&&!empty($pliego->pliegosobservacion->monto_solventado))?$pliego->pliegosobservacion->monto_solventado:0));
            }
    
            $TAP=$TSP+$TPP;
            $TAPS=$TSPS+$TPPS;
            $TAPNS=$TSPNS+$TPPNS;
           

           
            $pdf = Pdf::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadView('cedulageneral.show',compact('auditoria','TAP','TAPS','TAPNS','TSP','TSPS','TSPNS','TPP','TPPS','TPPNS'))->setPaper('a4', 'landscape')->stream('archivo.pdf');
            $nombre='storage/temporales/CedulaGeneral'.str_replace("/", "_", $auditoria->numero_auditoria).'.pdf';
            $pdfgenrado = file_put_contents($nombre, $pdf);

           
        }else{            
             $nombre=$auditoria->cedulageneralseguimiento[0]->cedula;           
        }

        $resultado=[
            'nombre'=>$nombre,
            'analistasF'=>$analistasF,
            'analistasL'=>$analistasL,
            'lideresF'=>$lideresF,
            'lideresL'=>$lideresL,
            'jefesF'=>$jefesF,
            'jefesL'=>$jefesL,
        ];

        return $resultado;
    }
}
