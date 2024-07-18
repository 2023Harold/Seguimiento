<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use App\Models\Constancia;
use Illuminate\Http\Request;

class ConstanciaController extends Controller
{
    public function mostrarConstancia(Constancia $constancia, $rutaCerrar){
       
        $auditoria=Auditoria::find(getSession('auditoria_id'));
        
        
        return view('constancia.mostrarconstancia', compact('constancia', 'rutaCerrar','auditoria'));
    }
}
