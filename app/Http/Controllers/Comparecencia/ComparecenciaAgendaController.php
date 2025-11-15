<?php

namespace App\Http\Controllers\Comparecencia;

use App\Http\Controllers\Controller;
use App\Models\Comparecencia;
use App\Models\ComparecenciaAgenda;
use App\Models\Movimientos;
use App\Models\Radicacion;
use App\Rules\TiempoRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ComparecenciaAgendaController extends Controller
{
    public $validationRules;
    public $attributeNames;
    public $errorMessages;
    public function __construct()
    {
        $this->validationRules = [];
        $this->attributeNames = [           
            'hora_fin' => 'Hora aproximada de término',            
        ];
        $this->errorMessages = [
            'required' => 'El campo :attribute es obligatorio.',       
        ];
    }
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
    public function edit(Comparecencia $comparecencia)
    {
        $auditoria=$comparecencia->auditoria;
        $radicacion = $auditoria->radicacion;
        $comparecencia = $auditoria->comparecencia;
        $agenda=$comparecencia->agenda;
        //dd($agenda->count()>0 && !empty($agenda->sala));
        $citas=null;
        if (!empty($agenda) && $agenda->count()>0  ) {
            $citas=ComparecenciaAgenda::where('fecha',$comparecencia->fecha_comparecencia)->where('sala',$comparecencia->agenda->sala)->get();
        }
        
            

        return view('comparecenciaagenda.form', compact('radicacion','auditoria','comparecencia','citas'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comparecencia $comparecencia)
    {
        $comparecenciaagenda = ComparecenciaAgenda::where('id_comparecencia',$comparecencia->id)->first();

        $this->setValidator($request,$comparecenciaagenda)->validate(); 
        $request['sala']=intval(str_replace("s", "", $request->sala));   
        
        
        if (empty($comparecenciaagenda->id_comparecencia)) {
            $request['usuario_creacion_id'] = auth()->user()->id;
            $request['id_comparecencia'] = $comparecencia->id;
            $comparecenciaagenda = ComparecenciaAgenda::create($request->all());
        } else {
            $request['usuario_modificacion_id'] = auth()->user()->id;
            $comparecenciaagenda->update($request->all());
        }
        
        $auditoria = $comparecencia->auditoria;
        $radicacion = $auditoria->radicacion;
        Movimientos::create([
            'tipo_movimiento' => 'Registro de la radicación',
            'accion' => 'Radicación',
            'accion_id' => $radicacion->id,
            'estatus' => 'Aprobado',
            'usuario_creacion_id' => auth()->id(),
            'usuario_asignado_id' => auth()->id(),
        ]);        

        if (strlen($radicacion->nivel_autorizacion) == 3) {
            $nivel_autorizacion = $radicacion->nivel_autorizacion;
        } else {
            $nivel_autorizacion = substr(auth()->user()->unidad_administrativa_id, 0, 4);
        }

       if(getSession('cp')==2022){
            $radicacion->update(['fase_autorizacion' =>  'En validación', 'nivel_autorizacion' => $nivel_autorizacion]);
            //$notificacion=auth()->user()->notificaciones()->where('llave',GenerarLlave( $radicacion).'/RevJD')->first();
            $notificacionRechazo=auth()->user()->notificaciones()->where('llave',GenerarLlave($radicacion)."/Rechazo")->first();
            //$LeerNotificacion = auth()->user()->NotMarcarLeido($notificacion);
            $LeerNotificacionR = auth()->user()->NotMarcarLeido($notificacionRechazo);
            $url = route('radicacion.index');

            $titulo = 'Validación de los datos de radicación';
            $mensaje = '<strong>Estimado (a) ' . auth()->user()->director->name . ', ' . auth()->user()->director->puesto . ':</strong><br>
                        Ha sido registrada la radicación de la auditoría No. ' . $radicacion->auditoria->numero_auditoria . ', por parte del ' . 
                        auth()->user()->puesto.' '.auth()->user()->name . ', por lo que se requiere realice la validación.';
    
            auth()->user()->insertNotificacion($titulo, $mensaje, now(), auth()->user()->director->unidad_administrativa_id,auth()->user()->director->id,GenerarLlave($radicacion).'/ValD',$url); 
        
        
        }elseif(getSession('cp')!=2022){
            $radicacion->update(['fase_autorizacion' =>  'En revisión', 'nivel_autorizacion' => $nivel_autorizacion]);

            //$notificacion=auth()->user()->notificaciones()->where('llave',GenerarLlave( $radicacion).'/RevJD')->first();
            $notificacionRechazo=auth()->user()->notificaciones()->where('llave',GenerarLlave($radicacion)."/Rechazo")->first();
            //$LeerNotificacion = auth()->user()->NotMarcarLeido($notificacion);
            $LeerNotificacionR = auth()->user()->NotMarcarLeido($notificacionRechazo);
            $url = route('radicacion.index');

            $titulo = 'Revisión de los datos de radicación';
            $mensaje = '<strong>Estimado (a) ' . auth()->user()->jefe->name . ', ' . auth()->user()->jefe->puesto . ':</strong><br>
                        Ha sido registrada la radicación de la auditoría No. ' . $radicacion->auditoria->numero_auditoria . ', por parte del ' . 
                        auth()->user()->puesto.' '.auth()->user()->name . ', por lo que se requiere realice la validación.';
    
            auth()->user()->insertNotificacion($titulo, $mensaje, now(), auth()->user()->jefe->unidad_administrativa_id,auth()->user()->jefe->id,GenerarLlave($radicacion).'/RevJD',$url);        
        
        }
          

      
        
        return redirect()->route('radicacion.index');
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function validar(Request $request)
    {
        //dd('hola');
        //$query=new ComparecenciaAgenda();
        
        //dd($reunionesagendadas);




        return $request;
        
    }

    protected function setValidator(Request $request,ComparecenciaAgenda $agenda = null)
    {        
        $idagenda=(empty($agenda)?'N/A':$agenda->id);

        $this->validationRules['hora_fin'] = ['required', 'string', 'max:10',  new TiempoRule($request->hora_inicio,$request->fecha,$request->sala,$idagenda)];
        
        return Validator::make($request->all(), $this->validationRules, $this->errorMessages)->setAttributeNames($this->attributeNames);
    }
}
