<?php

namespace App\Http\Controllers;

use App\Models\CuentaPublica;
use App\Models\Notificacion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PasarNotificacionesController extends Controller
{

    protected $validationRules;
    protected $attributeNames;
    protected $errorMessages;
    protected $model;

    public function __construct(User $model)
    {
      $this->model = $model;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        //dd("Index pasar notificaciones");
        $users = $this->setQuery($request)->paginate(20);
        return view('administracion.pasarnotificaciones.index', compact('request','users'));
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
    
    public function edit(Request $request, User $user)
    {
        $hoy = now();

        // Notificaciones pendientes del usuario actual
        $NotUsuario = Notificacion::where('destinatario_id', $user->id)
            ->whereNull('fecha_muestra_fin')
            ->where('fecha_muestra_inicio', '<=', $hoy)
            ->where('estatus', 'Pendiente')
            ->orderBy('fecha_muestra_inicio', 'asc')
            ->get();

        // Construir lista: id => nombre
        // OJO: excluimos al usuario actual
        // Opción A (con accessor full_name):
        /**
        $users = User::query()
            ->where('id', '!=', $user->id)
            ->orderBy('name') // o 'name' si es tu columna
            ->get()
            ->pluck('full_name', 'id'); // gracias al accessor
        */

        // Opción B (si NO quieres accessor y concatenas en Oracle):
         $users = User::query()
             ->where('id', '!=', $user->id)
             ->select('id', DB::raw("(name) as full_name"))
             ->orderBy(DB::raw("name"))
             ->get()
             ->pluck('full_name', 'id');

        return view('administracion.pasarnotificaciones.form', compact('user', 'NotUsuario', 'users'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
       //dd($request);
        
        $nuevoUsuarioId = $request->input('nuevo_usuario_id'); // del formulario
        $hoy = now();

        // Solo las pendientes y vigentes
        $afectadas = Notificacion::where('destinatario_id', $user->id)
            ->whereNull('fecha_muestra_fin')
            ->where('fecha_muestra_inicio', '<=', $hoy)
            ->where('estatus', 'Pendiente')
            ->update([
                'destinatario_id' => $nuevoUsuarioId,
                // Si necesitas auditar:
                // 'modificado_por' => auth()->id(),
                // 'fecha_modificacion' => now(),
            ]);

        flash("Se pasaron {$afectadas} notificaciones pendientes al usuario seleccionado.")->success();

        return redirect()->route('pasarnotificaciones.index');

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
    private function setQuery($request)
    {
        $query = $this->model;
        $query = $query->orderBy('id','DESC');
        if ($request->filled('name')) {
             $query = $query->whereLike('name', $request->name);
        }
        if($request->filled('email')){
            $query = $query->whereLike('email',$request->email);
        }
        
        return $query;
    }
}
