<?php

namespace App\Http\Controllers\Buzones;

use App\Http\Controllers\Controller;
use App\Models\Notificacion;
use Illuminate\Http\Request;
use App\Models\Auditoria;


class BuzonNotificacionPendientesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
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

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, )
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $buzon)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {

		//
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        dd("destroy");

    }


    public function marcarleido(Request $request)
    {
        $notificacion = Notificacion::find($request->id);
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

        $query = $query->where('estatus', '=', 'Pendiente');
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
        return $query->orderBy('created_at', 'desc');
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
