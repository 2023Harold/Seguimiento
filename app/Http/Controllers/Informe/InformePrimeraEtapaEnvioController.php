<?php

namespace App\Http\Controllers\Informe;

use App\Models\Auditoria;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Models\InformePrimeraEtapa;
use App\Models\Movimientos;
use Illuminate\Http\Request;

class InformePrimeraEtapaEnvioController extends Controller
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
    public function edit(InformePrimeraEtapa $auditoria)
    {
        $url =  route("informeprimeraetapa.index");
		$asistenteATUS=User::where('siglas_rol','ATUS')->first();
        $informeprimeraetapa=$auditoria;
        $auditoria=Auditoria::find(getSession('auditoria_id')); 
        
        $notificacion=auth()->user()->notificaciones()->where('llave', GenerarLlave($informeprimeraetapa).'/ValD')->first();
        $notificacionRechazo=auth()->user()->notificaciones()->where('llave', GenerarLlave($informeprimeraetapa).'/Rechazo')->first();
        $LeerNotificacion = auth()->user()->NotMarcarLeido($notificacion);
        $LeerNotificacionR = auth()->user()->NotMarcarLeido($notificacionRechazo);

        $informeprimeraetapa->update(['fase_autorizacion' =>  'En validación']);
        $url =  route("informeprimeraetapa.index");
        $titulo = 'de los datos del Informe Primera Etapa de '.$informeprimeraetapa->tipo ;
        $mensaje = '<strong>Estimado (a) ' . auth()->user()->director->name . ', ' . auth()->user()->director->puesto . ':</strong><br>
                    Ha sido registrado el Informe Primera Etapa  de la auditoría No. ' . $auditoria->numero_auditoria . ', por parte del ' .
                    auth()->user()->puesto.' '.auth()->user()->name . ', por lo que se requiere realice la validación.';
    
        auth()->user()->insertNotificacion("Validación ".$titulo, $mensaje, now(), auth()->user()->director->unidad_administrativa_id,auth()->user()->director->id, GenerarLlave($informeprimeraetapa).'/ValD', $url);
		auth()->user()->insertNotificacion("Revisión ".$titulo, $this->mensajeNotificacion($asistenteATUS->name,$asistenteATUS->puesto,$auditoria), now(), $asistenteATUS->unidad_administrativa_id,$asistenteATUS->id, GenerarLlave($informeprimeraetapa).'/Consulta', $url); 

		Movimientos::create([
            'tipo_movimiento' => 'Registro del Informe Primera Etapa',
            'accion' => 'InformePrimeraEtapa',
            'accion_id' => $informeprimeraetapa->id,
            'estatus' => 'Aprobado',
            'usuario_creacion_id' => auth()->id(),
            'usuario_asignado_id' => auth()->id(),
        ]);
        setMessage('Se ha enviado el Informe Primera Etapa, a validación');
    
        return redirect()->route('informeprimeraetapa.index');
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
        //
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
	
	private function mensajeNotificacion(String $nombre, String $puesto,  $auditoria)
    {
        $mensaje = '<strong>Estimado(a) '.$nombre.', '.$puesto.':</strong><br>'
                    .'Ha sido registrado el Informe Primera Etapa  de la auditoría No.  '.$auditoria->numero_auditoria.', por lo que se debe revisar.';    

        return $mensaje;
    }
}
