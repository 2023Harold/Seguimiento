<?php

namespace App\Http\Controllers;

use App\Models\Notificacion;
use Illuminate\Http\Request;

class NotificacionController extends Controller
{
    public function index(Request $request){
        /*
        $notificaciones = $this->setQuery($request)->paginate(25);
        
        return view('notificaciones.index', compact('notificaciones','request'));*/

        $notificaciones = $this->setQuery($request)->paginate(25);

        return view('notificaciones.index', compact('notificaciones', 'request'));
    }

    /** 
    public function marcarleido(Request $request)
    {
        $notificacion = Notificacion::find($request->id);
        $notificacion->update(['estatus' => 'Leído']);

        return $request->id;
    }
        */
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
