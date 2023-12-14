<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use App\Models\Cedula;
use App\Models\Movimientos;
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
        if(count($auditoria->cedulageneralseguimiento)==0){
            $TSP=0;
            $TSPS=0;
            $TSPNS=0;
    
            foreach ($auditoria->totalsolacl as $solicitud) {
                $TSP=$TSP+$solicitud->monto_aclarar;
                $TSPS=$TSPS+$solicitud->solicitudesaclaracion->monto_solventado;
                $TSPNS=$TSPNS+($solicitud->monto_aclarar-$solicitud->solicitudesaclaracion->monto_solventado);
            }
    
            //dd($totalSolicitudesPromovidas,$totalSolicitudesPromovidasSolventadas,$totalSolicitudesPromovidasNoSolventadas);
            $TPP=0;
            $TPPS=0;
            $TPPNS=0;
    
            foreach ($auditoria->totalpliegos as $pliego) {
                $TPP=$TPP+$pliego->monto_aclarar;
                $TPPS=$TPPS+$pliego->pliegosobservacion->monto_solventado;
                $TPPNS=$TPPNS+($pliego->monto_aclarar-$pliego->pliegosobservacion->monto_solventado);
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
       
        
        return view('cedulageneral.index',compact('nombre','auditoria'));
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
            $request['auditoria_id']=$auditoria->id;
            $request['cedula_tipo']='Cedula General Seguimiento';
            $request['usuario_creacion_id']=auth()->id();        
            $request['cedula']=$request->cedula2;
            mover_archivos($request, ['cedula']);
            $cedula=Cedula::create($request->all());
        }else{
            $cedula=$auditoria->cedulageneralseguimiento[0];
        }

        Movimientos::create([
            'tipo_movimiento' => 'Registro de la Cédula General de Seguimiento',
            'accion' => 'Cédula General de Seguimiento',
            'accion_id' => $auditoria->id,
            'estatus' => 'Aprobado',
            'usuario_creacion_id' => auth()->id(),
            'usuario_asignado_id' => auth()->id(),

        ]);

       
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
    
    
        setMessage('Se ha enviado a revisión la cedula general de seguimiento.');
       
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
}
