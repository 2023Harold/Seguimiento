<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuditoriaRequest;
use App\Models\Auditoria;
use App\Models\AuditoriaAccion;
use App\Models\Cedula;
use App\Models\Movimientos;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class CedulaInicialAutorizacionController extends Controller
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
    public function edit(Cedula $cedula)
    {
        $auditoria = Auditoria::find(getSession('auditoria_id'));
        $nombre=$cedula->cedula;
       
        return view('cedulageneralautorizacion.form',compact('nombre','auditoria','cedula'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cedula $cedula)
    {
        $auditoria = Auditoria::find(getSession('auditoria_id'));        

        $this->normalizarDatos($request);

        Movimientos::create([
            'tipo_movimiento' => 'Autorización de la Cédula General de Seguimiento',
            'accion' => 'Cédula General de Seguimiento',
            'accion_id' => $auditoria->id,
            'estatus' => $request->estatus,
            'usuario_creacion_id' => auth()->id(),
            'usuario_asignado_id' => auth()->id(),
            'motivo_rechazo' => $request->motivo_rechazo,
        ]);       

        $cedula->update([
            'fase_autorizacion' => $request->estatus == 'Aprobado' ? 'Autorizado' : 'Rechazado'
        ]);
            $director=User::where('unidad_administrativa_id',substr($cedula->userCreacion->unidad_administrativa_id, 0, 4).'00')->where('siglas_rol','DS')->first();
            $jefe=User::where('unidad_administrativa_id',substr($cedula->userCreacion->unidad_administrativa_id, 0, 5).'0')->where('siglas_rol','JD')->first();

               
            $accionesanalistasListos=AuditoriaAccion::whereNotNull('aprobar_cedini_analista')->where('segauditoria_id',$auditoria->id)->get(); 
            $accionesLideresListos=AuditoriaAccion::whereNotNull('aprobar_cedini_lider')->where('segauditoria_id',$auditoria->id)->get();  
            $accionesJefesListos=AuditoriaAccion::whereNotNull('aprobar_cedini_jefe')->where('segauditoria_id',$auditoria->id)->get();    
            $analistasL=array_unique($accionesanalistasListos->pluck('analista_asignado_id', 'id')->toArray());
            $nombresanalistasL=array_unique($accionesanalistasListos->pluck('analista_asignado', 'id')->toArray());
            $lideresL=array_unique($accionesLideresListos->pluck('lider_asignado_id', 'id')->toArray());
            $nombreslideresL=array_unique($accionesLideresListos->pluck('lider_asignado', 'id')->toArray());
            $jefesL=array_unique($accionesJefesListos->pluck('departamento_asignado_id', 'id')->toArray());
            $nombresJefesL=[];

            if(count($jefesL)>0){            
                foreach($jefesL as $jefeid){
                    $jefe=User::where('unidad_administrativa_id',$jefeid)->first();
                    $nombresJefesL[$jefe->id] = $jefe->name;
                }
            }
            
            $TSP=0;
            $TSPS=0;
            $TSPNS=0;
    
            foreach ($auditoria->totalsolacl as $solicitud) {
                $TSP=$TSP+$solicitud->monto_aclarar;
                $TSPS=$TSPS+((!empty($solicitud->solicitudesaclaracion)&&!empty($solicitud->solicitudesaclaracion->monto_solventado))?$solicitud->solicitudesaclaracion->monto_solventado:0);
                $TSPNS=$TSPNS+($solicitud->monto_aclarar-((!empty($solicitud->solicitudesaclaracion)&&!empty($solicitud->solicitudesaclaracion->monto_solventado))?$solicitud->solicitudesaclaracion->monto_solventado:0));
            }
    
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
                
            $pdf = Pdf::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadView('cedulageneral.show',compact('auditoria','TAP','TAPS','TAPNS','TSP','TSPS','TSPNS','TPP','TPPS','TPPNS','director','nombresanalistasL','nombreslideresL','nombresJefesL'))->setPaper('a4', 'landscape')->stream('archivo.pdf');
            $nombre='storage/temporales/CedulaGeneral'.str_replace("/", "_", $auditoria->numero_auditoria).'.pdf';
            $pdfgenrado = file_put_contents($nombre, $pdf);
            

            $request['cedulaaut']=$nombre;
            mover_archivos($request, ['cedulaaut']);
            $cedula->update(['cedula' => $request->cedulaaut]);

        if ($request->estatus == 'Aprobado') {
            $titulo = 'Autorización del la Cédula General de Seguimiento de la Auditoría No. '.$auditoria->numero_auditoria;

            auth()->user()->insertNotificacion($titulo, $this->mensajeAprobado($director->name,$director->puesto,$auditoria), now(), $director->unidad_administrativa_id, $director->id);
            auth()->user()->insertNotificacion($titulo, $this->mensajeAprobado($jefe->name,$jefe->puesto,$auditoria), now(), $jefe->unidad_administrativa_id, $jefe->id);
          

            setMessage('Se ha autorizado la Cédula General de Seguimiento.');
        } else {
            AuditoriaAccion::where('segauditoria_id',$auditoria->id)->update(['aprobar_cedini_jefe'=>null,'aprobar_cedini_lider'=>null,'aprobar_cedini_analista'=>null]);
            $titulo = 'Rechazo de la Cédula General de Seguimiento de la Auditoría No. '.$auditoria->numero_auditoria;
            $mensaje = '<strong>Estimado(a) '.$cedula->userCreacion->name.', '.$cedula->userCreacion->puesto.':</strong><br>'
                            .'Ha sido rechazado la Cédula General de Seguimiento de la Auditoría No. '.$auditoria->numero_auditoria.
                            ', por lo que se debe atender los comentarios y enviar la información corregida nuevamente a revisión.';

            auth()->user()->insertNotificacion($titulo, $mensaje, now(), $cedula->userCreacion->unidad_administrativa_id, $cedula->userCreacion->id);
            auth()->user()->insertNotificacion($titulo, $this->mensajeRechazo($director->name,$director->puesto,$auditoria), now(), $director->unidad_administrativa_id, $director->id);
            auth()->user()->insertNotificacion($titulo, $this->mensajeRechazo($jefe->name,$jefe->puesto,$auditoria), now(), $jefe->unidad_administrativa_id, $jefe->id);
           

            setMessage('Se ha rechazado la Cédula General de Seguimiento.');
        }

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

    private function normalizarDatos(Request $request)
    {
        if ($request->estatus == 'Aprobado') {
            $request['motivo_rechazo'] = null;
        }

        return $request;
    }

    private function mensajeRechazo(String $nombre, String $puesto, Auditoria $auditoria)
    {
        $mensaje = '<strong>Estimado(a) '.$nombre.', '.$puesto.':</strong><br>'
                    .'Ha sido rechazado el registro de la Cédula General de Seguimiento de la Auditoría No. '.$auditoria->numero_auditoria.'.';

        return $mensaje;
    }

    private function mensajeAprobado(String $nombre, String $puesto, Auditoria $auditoria)
    {
        $mensaje = '<strong>Estimado(a) '.$nombre.', '.$puesto.':</strong><br>'
                    .' Ha sido aprobado el registro de la Cédula General de Seguimiento de la Auditoría No. '.$auditoria->numero_auditoria.
                    ', por parte del Titular.';

        return $mensaje;
    }
}
