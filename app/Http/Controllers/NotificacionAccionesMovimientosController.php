<?php

namespace App\Http\Controllers;

use App\Models\CuentaPublica;
use App\Models\Notificacion;
use Illuminate\Http\Request;

class NotificacionAccionesMovimientosController extends Controller
{
    public function index(Request $request){
        dd('index');
		$notificaciones = $this->setQuery($request)->paginate(25);
		

        return view('notificaciones.index', compact('notificaciones', 'request'));
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
    public function edit(Notificacion $notificacion)
    {
        
        dd('edit');

        $cp = CuentaPublica::where('cuenta_publica',$notificacion->cp)->get()->first();
        $usuario=auth()->user();

        if($cp->id==1){
            setSession('cp',2021);
            setSession('cp_ua',$usuario->cp_ua2021);
        }
        if($cp->id==2){
            setSession('cp',2022);
            setSession('cp_ua',$usuario->cp_ua2022);
        }
        if($cp->id==3){
            setSession('cp',2023);
            setSession('cp_ua',$usuario->cp_ua2023);
        }
        $envioNotificacion = $this->normalizarEnvio($notificacion);

        if($envioNotificacion == "Registro de auditoría"){
            return redirect()->route('seguimientoauditoriacp.index');
        }
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
        dd('update');
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
    public function marcarleido(Request $request)
    {
        $notificacion = Notificacion::find($request->id);
        $notificacion->update(['estatus' => 'Leído']);

        return $request->id;
    }
        */

    public function normalizarEnvio($notificacion01)
    {
        if($notificacion01->titulo=="Registro de auditoría"){
            $notificacion01 = "Registro de auditoría";
        }else{
            $notificacion01 = 6;
        }
        
        /*
       $request['entidad_fiscalizable'] = $entidadCompleta;
       $request['tipo_entidad']=(!empty($entidad->Ambito)?$entidad->Ambito:'');
       $request['siglas_entidad']=(!empty($entidad->SigEntFis)?$entidad->SigEntFis:'');
       $request['ejercicio']=0;
       $request['acto_fiscalizacion']=$tipoauditoria->descripcion;
       */

       return  $notificacion01;
    }

    public function marcarleido(Request $request)
    {
        $notificacion = Notificacion::find($request->id);
        // Actualizar el estatus a 'Leído'
        $notificacion->update(['estatus' => 'Leído']);

        // Retornar la respuesta con el id y la fecha de lectura formateada
        return response()->json([
            'id' => $notificacion->id,
            'fecha_leido' => $notificacion->updated_at->format('d/m/Y H:i'), // Formato de fecha
            $request->id
        ]);
    }


    

    private function setQuery($request)
    {
        $query = auth()->user()->todasNotificaciones();
        if ($request->filled('estatus') && $request->input('estatus') != 'Todos') {
            $query = $query->where('estatus', $request->input('estatus'));
        }
        if ($request->filled('created_at')) {
            $query = $query->whereDate('created_at', $request->input('created_at'));
        }
        if ($request->filled('updated_at')) {
            $query = $query->whereDate('updated_at', $request->input('updated_at'))->where('estatus', 'Leído');
        }
        if ($request->filled('numero_auditoria')) {
            $numeroAuditoria = $request->input('numero_auditoria');
            $query = $query->where('mensaje', 'LIKE', "%$numeroAuditoria%");
        }
		 if ($request->filled('cuenta') && $request->input('cuenta') != 'Todas') {
			$cp = str_replace("|", "", $request->input('cuenta'));
            $query = $query->where('cp', $cp);
        }

        return $query->orderBy('created_at', 'asc');
    }

    

    
    public function nuevas()
    {
        $notificaciones = auth()->user()->notificaciones;
        $totalNotificaciones = $notificaciones->count();

        return response()->json([
            'notificaciones' => $notificaciones,
            'total' => $totalNotificaciones,
        ]);
    }



    

}
