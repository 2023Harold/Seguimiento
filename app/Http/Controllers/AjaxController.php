<?php

namespace App\Http\Controllers;

use App\Models\Comparecencia;
use App\Models\ComparecenciaAgenda;
use Illuminate\Http\Request;

class AjaxController extends Controller
{
    public function getAgendaComparecencias(Request $request)
    {
        $datos = [];
        $citas = [];

        $reuniones = ComparecenciaAgenda::where('sala', intval(str_replace("s", "", $request->numerosala)))->where('fecha',$request->fecha)->get();     

        if (!empty($reuniones) && count($reuniones) > 0) {
            foreach ($reuniones as $reunion) {
                $citas[] = ['id_comparecencia'=>$reunion->id_comparecencia,'sala' => $reunion->sala, 'fecha' => fecha($reunion->fecha),'hora_inicio'=> date("g:i a",strtotime($reunion->hora_inicio)),'hora_fin'=>date("g:i a",strtotime($reunion->hora_fin))];
            }
        }       

        $datos[0] = $citas;

        return response()->json($datos);
    }
}
