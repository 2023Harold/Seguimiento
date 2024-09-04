<?php

namespace App\Http\Controllers;

use App\Http\Requests\AprobarFlujoAutorizacionRequest;
use App\Models\Auditoria;
use App\Models\Movimientos;
use App\Models\Radicacion;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use Luecano\NumeroALetras\NumeroALetras;
use PhpOffice\PhpWord\TemplateProcessor;


class RadicacionAutorizacionController extends Controller
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
    public function edit(Radicacion $radicacion)
    {
        $auditoria=$radicacion->auditoria;

        $horas=explode(':',$auditoria->comparecencia->hora_comparecencia_inicio); 
        $formatter = new NumeroALetras();
        $hora = $formatter->toString($horas[0]);
        $minutos = $formatter->toString($horas[1]);
      
        $horaMax = ucwords($hora);             
        $horaMin = ucwords(strtolower($horaMax));
       
        $minutosMax = ucwords($minutos);            
        $minutosMin = ucwords(strtolower($minutosMax));

        $fechacomparecencia=fechaaletra($auditoria->comparecencia->fecha_comparecencia);        
        $fechainicioaclaracion=fechaaletra($auditoria->comparecencia->fecha_inicio_aclaracion);       
        $fechaterminoaclaracion=fechaaletra($auditoria->comparecencia->fecha_termino_aclaracion);

        $formatterPM = new NumeroALetras();
        $plazomax=$formatter->toString($radicacion->plazo_maximo);

        $plazomaxMax = ucwords($plazomax);            
        $plazomaxMin = ucwords(strtolower($plazomaxMax));

        $fechaactual=fechaaletra(now());
        
        $relacion4=[
            'horastxt'=> $horaMin,
            'mintxt'=> $minutosMin,
            'fechacomparecenciatxt'=>$fechacomparecencia,
            'fechainicioaclaraciontxt'=>$fechainicioaclaracion,
            'fechaterminoaclaraciontxt'=>$fechaterminoaclaracion,
            'plazomaximo'=>$plazomaxMin,
            'fechaactual'=>$fechaactual,
        ];

        $datosConstancia = [
            'nombrereporte' => 'radicacionconstancia',
            'auditoriaseleccionada'=>base64_encode(Str::random(5).$radicacion->auditoria_id.Str::random(5)),
            'accionseleccionada'=>'',            
            'modelo_principal'=>['tbl'=>$radicacion->getTable(),'vinculo'=>base64_encode(Str::random(5).$radicacion->id.Str::random(5))],
            'relacion1'=>null,
            'relacion2'=>null,
            'relacion3'=>null,
            'relacion4'=>$relacion4,  
            'firmante'=>auth()->user()->name,
            'firmante_puesto'=>auth()->user()->puesto,  
        ];

        $b64archivoxml=reportepdf($datosConstancia['nombrereporte'],1,'Temporal',
                                 base64_encode(Str::random(5).$radicacion->auditoria_id.Str::random(5)),
                                 '',
                                 ['tbl'=>$radicacion->getTable(),'vinculo'=>base64_encode(Str::random(5).$radicacion->id.Str::random(5))],
                                 null,null,null,$relacion4,'','','','','','','');

        $preconstancia ='/storage/temporales/'.$datosConstancia['nombrereporte'] .'.pdf';

        
         return view('radicacionautorizacion.form', compact('radicacion', 'auditoria', 'preconstancia', 'b64archivoxml', 'datosConstancia'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AprobarFlujoAutorizacionRequest $request, Radicacion $radicacion)
    {
       
        $this->normalizarDatos($request);
        if(empty($request->radicacion_sistema)){

            Movimientos::create([
                'tipo_movimiento' => 'Autorización de la radicación',
                'accion' => 'Radicación',
                'accion_id' => $radicacion->id,
                'estatus' => $request->estatus,
                'usuario_creacion_id' => auth()->id(),
                'usuario_asignado_id' => auth()->id(),
                'motivo_rechazo' => $request->motivo_rechazo,
            ]);       
           
            $radicacion->update([
                'fase_autorizacion' => $request->estatus == 'Aprobado' ? 'Autorizado' : 'Rechazado',
            ]);



        }else{
            //dd($request);
            //$ruta = env('APP_RUTA_MINIO').'Expedientes/' . strtoupper(Str::slug($cierre->denunciado->expediente->carpeta_expediente)).'/Constancias';
            $constancia = guardarConstanciasFirmadas($radicacion, 'constancia_radicacion', $request, 'constancia');

            Movimientos::create([
                'tipo_movimiento' => 'Autorización de la radicación',
                'accion' => 'Radicación',
                'accion_id' => $radicacion->id,
                'estatus' => $request->estatus,
                'usuario_creacion_id' => auth()->id(),
                'usuario_asignado_id' => auth()->id(),
                'motivo_rechazo' => $request->motivo_rechazo,
            ]);       
        
            $radicacion->update([
                'fase_autorizacion' => $request->estatus == 'Aprobado' ? 'Autorizado' : 'Rechazado',
                'constancia' => $constancia->constancia_pdf,
            ]);
        }

        
        $director=User::where('unidad_administrativa_id',substr($radicacion->auditoria->unidad_administrativa_registro, 0, 4).'00')->where('siglas_rol','DS')->first();
        if ($request->estatus == 'Aprobado') {
            $titulo = 'Autorización del registro de la radiación de la auditoría No. '.$radicacion->auditoria->numero_auditoria;
            
            auth()->user()->insertNotificacion($titulo, $this->mensajeAprobado($radicacion->usuarioCreacion->name,$radicacion->usuarioCreacion->puesto,$radicacion->auditoria->numero_auditoria), now(), $radicacion->usuarioCreacion->unidad_administrativa_id, $radicacion->usuarioCreacion->id);
            auth()->user()->insertNotificacion($titulo, $this->mensajeAprobado($director->name,$director->puesto,$radicacion->auditoria->numero_auditoria), now(), $director->unidad_administrativa_id, $director->id);
            
            setMessage('Se ha autorizado el registro de la radicación de la auditoría con exito.');
        } else {
            $titulo = 'Rechazo del registro de radiación de la auditoría No. '.$radicacion->auditoria->numero_auditoria;
            $mensaje = '<strong>Estimado(a) '.$radicacion->usuarioCreacion->name.', '.$radicacion->usuarioCreacion->puesto.':</strong><br>'
                            .'Ha sido rechazado el registro de la radicación de auditoría No. '.$radicacion->auditoria->numero_auditoria.
                            ', por lo que se debe atender los comentarios y enviar la información corregida nuevamente a revisión.';
            
            auth()->user()->insertNotificacion($titulo, $mensaje, now(), $radicacion->usuarioCreacion->unidad_administrativa_id, $radicacion->usuarioCreacion->id);           
            auth()->user()->insertNotificacion($titulo, $this->mensajeRechazo($director->name,$director->puesto,$radicacion->auditoria->numero_auditoria), now(), $director->unidad_administrativa_id, $director->id);
            
            setMessage('Se ha rechazado el registro de la radicación de la auditoría con exito.');
            
        }    

        if(empty($request->radicacion_sistema)){

            return redirect()->route('radicacion.index');
        } else{
            
            return redirect()->route('constancia.mostrarConstancia', ['constancia'=>$constancia, 'rutaCerrar'=>'radicacion.index']);
        }
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
                    .'Ha sido rechazado el registro de la radicación de auditoría No. '.$numeroauditoria.'.';       

        return $mensaje;
    }

    private function mensajeAprobado(String $nombre, String $puesto, String $numeroauditoria)
    {
        $mensaje = '<strong>Estimado(a) '.$nombre.', '.$puesto.':</strong><br>'
                    .' Ha sido autorizado el registro de radicación de la auditoría No. '.$numeroauditoria.
                    ', por parte del Titular.';       

        return $mensaje;
    }

}
