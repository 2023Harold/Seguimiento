<?php

namespace App\Http\Controllers\AcuerdoConclusion;

use App\Http\Controllers\Controller;
use App\Http\Requests\AprobarFlujoAutorizacionRequest;
use App\Models\AcuerdoConclusion;
use App\Models\Auditoria;
use App\Models\Movimientos;
use App\Models\User;
use Illuminate\Http\Request;

class AcuerdoConclusionAutorizacionController extends Controller
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
    public function edit(AcuerdoConclusion $auditoria)
    {
        $acuerdoconclusion=$auditoria;
        $auditoria = Auditoria::find($acuerdoconclusion->auditoria_id);
       
        return view('acuerdoconclusionautorizacion.form', compact('acuerdoconclusion','auditoria'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AprobarFlujoAutorizacionRequest $request, AcuerdoConclusion $auditoria)
    {
        $url = route('acuerdoconclusion.index');
        $this->normalizarDatos($request);
        $acuerdoconclusion=$auditoria;
        $mensaje = "";
        $auditoria = Auditoria::find($acuerdoconclusion->auditoria_id);
        $Atus=User::where('siglas_rol','ATUS')->first();

        if ($request->estatus == 'Aprobado') {
            $nivel_autorizacion = substr(auth()->user()->unidad_administrativa_id, 0, 3);
        } else {
            $nivel_autorizacion = substr(auth()->user()->unidad_administrativa_id, 0, 4);
        }

        if(getSession('cp')==2022){
            $jefe=User::where('unidad_administrativa_id', substr($auditoria->userCreacion->unidad_administrativa_id, 0, 5).'0')->first();
            $lider=$auditoria->accion->lider;
        }else{
            $jefe = $auditoria->jefedepartamentoencargado;
            $lider = $auditoria->lidercp; 
        }
        $director=User::where('unidad_administrativa_id',substr($lider->unidad_administrativa_id, 0, 4).'00')->where('siglas_rol','DS')->first();


        $acuerdoconclusion->update(['fase_autorizacion' => $request->estatus == 'Aprobado' ? 'Autorizado' : 'Rechazado', 'nivel_autorizacion' => $nivel_autorizacion]);
        setMessage($request->estatus == 'Aprobado' ?
            'Se ha autorizado el Acuerdo de Conclusión de la auditoría con exito.' :
            'El rechazo ha sido registrado.'
        );

        if ($request->estatus == 'Aprobado') {
            $titulo = 'Acuerdo de conclusión de '.$acuerdoconclusion->tipo.' Autorizado';
            $mensaje = '<strong>Estimado(a) '.$lider->name.', '.$lider->puesto.':</strong><br>'
                            .'Se ha autorizado el Acuerdo de conclusión de la auditoría No. '.$acuerdoconclusion->auditoria->numero_auditoria.', por lo que se deben adjuntar los acuses correspondientes';                          
            auth()->user()->insertNotificacion($titulo, $mensaje, now(), $lider->unidad_administrativa_id, $lider->id, GenerarLlave($acuerdoconclusion).'/'.$acuerdoconclusion->tipo.'/Consulta',$url);
            auth()->user()->insertNotificacion($titulo, $mensaje, now(), $director->unidad_administrativa_id, $director->id, GenerarLlave( $acuerdoconclusion).'/'.$acuerdoconclusion->tipo.'/Consulta',$url);
            auth()->user()->insertNotificacion($titulo, $mensaje, now(), $jefe->unidad_administrativa_id,$jefe->id,GenerarLlave( $acuerdoconclusion).'/'.$acuerdoconclusion->tipo.'/Consulta',$url);
            auth()->user()->insertNotificacion($titulo, $mensaje, now(), $Atus->unidad_administrativa_id, $Atus->id,GenerarLlave( $acuerdoconclusion).'/'.$acuerdoconclusion->tipo.'/Consulta',$url ); 

        }else {
            
             $titulo = 'Acuerdo de Conclusión de '.$acuerdoconclusion->tipo.' Rechazado';
             $mensaje = '<strong>Estimado(a) '.$lider->name.', '.$lider->puesto.':</strong><br>'
                             .'Ha sido rechazado el Acuerdo de Conclusión de la auditoría No. '.$acuerdoconclusion->auditoria->numero_auditoria.
                             ', por lo que se debe atender los comentarios y enviar la información corregida nuevamente a autorización.';
            
            auth()->user()->insertNotificacion($titulo, $mensaje, now(), $lider->unidad_administrativa_id, $lider->id, GenerarLlave($acuerdoconclusion).'/'.$acuerdoconclusion->tipo.'/Rechazo',$url);
            auth()->user()->insertNotificacion($titulo, $this->mensajeRechazo($director->name,$director->puesto,$acuerdoconclusion->auditoria->numero_auditoria),now(), $director->unidad_administrativa_id, $director->id, GenerarLlave( $acuerdoconclusion).'/'.$acuerdoconclusion->tipo.'/Rechazo',$url);
            auth()->user()->insertNotificacion($titulo, $this->mensajeRechazo($jefe->name, $jefe->puesto, $acuerdoconclusion->auditoria->numero_auditoria),now(), $jefe->unidad_administrativa_id, $jefe->id, GenerarLlave($acuerdoconclusion).'/'.$acuerdoconclusion->tipo.'/Rechazo',$url);        
            auth()->user()->insertNotificacion($titulo, $this->mensajeRechazo($Atus->name, $Atus->puesto,$acuerdoconclusion->auditoria->numero_auditoria), now(), $Atus->unidad_administrativa_id, $Atus->id,GenerarLlave( $acuerdoconclusion).'/'.$acuerdoconclusion->tipo.'/Rechazo',$url ); 
        }
        $notificacion=auth()->user()->notificaciones()->where('llave',GenerarLlave( $acuerdoconclusion).'/'.$acuerdoconclusion->tipo.'/Aut')->first();
        $LeerNotificacion = auth()->user()->NotMarcarLeido($notificacion);
        $NotificacionRechazo=auth()->user()->notificaciones()->where('llave',GenerarLlave( $acuerdoconclusion).'/'.$acuerdoconclusion->tipo.'/Rechazo')->first();
        $LeerNotificacionRechazo = auth()->user()->NotMarcarLeido($NotificacionRechazo);
        Movimientos::create([
            'tipo_movimiento' => 'Autorización del acuerdo de conclusión ',
            'accion' => 'AcuerdoConclusion',
            'accion_id' => $acuerdoconclusion->id,
            'estatus' => $request->estatus,
            'usuario_creacion_id' => auth()->id(),
            'usuario_asignado_id' => auth()->id(),
            'motivo_rechazo' => $request->motivo_rechazo,
        ]);
        return redirect()->route('acuerdoconclusion.index');
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
    private function mensajeRechazo(String $nombre, String $puesto, String $numeroauditoria)
    {
        $mensaje = '<strong>Estimado(a) '.$nombre.', '.$puesto.':</strong><br>'
                    .'Ha sido rechazado el registro del Acuerdo de Conclusión de las auditoría No. '.$numeroauditoria.', por lo que se debe atender y enviar la información corregida nuevamente.';;       

        return $mensaje;
    }

    private function mensajeAprobado(String $nombre, String $puesto, String $numeroauditoria)
    {
        $mensaje = '<strong>Estimado(a) '.$nombre.', '.$puesto.':</strong><br>'
                    .' Ha sido autorizado el registro de Acuerdo de conclusion de la auditoría No. '.$numeroauditoria.
                    ', por parte del Titular.';       

        return $mensaje;
    }
}
