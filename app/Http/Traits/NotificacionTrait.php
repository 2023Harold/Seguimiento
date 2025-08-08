<?php

namespace App\Http\Traits;

use App\Models\Notificacion;

trait NotificacionTrait
{
    public function insertNotificacion($titulo, $mensaje, $inicio, $unidad = null, $destinatario = null, $llave = null, $url = null)
    {
        $params = [
            'titulo' => $titulo,
            'mensaje' => $mensaje,
            'fecha_muestra_inicio' => $inicio,
            'estatus' => 'Pendiente',
            'unidad_administrativa_id' => $unidad,
            'destinatario_id' => $destinatario,
            'usuario_creacion_id' => (empty(auth()->user()->id) ? 1 : auth()->user()->id),
			'cp'=> (empty(getSession('cp')) ? 1 : getSession('cp')),
            'llave' => $llave,
            'url' => $url,
        ];

        Notificacion::create($params);
    }
}