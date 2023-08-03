<?php

namespace App\Http\Controllers;

use App\Models\Constancia;
use Illuminate\Http\Request;

class ConstanciaController extends Controller
{
    public function mostrarConstancia(Constancia $constancia, $rutaCerrar){
        
        return view('constancia.mostrarconstancia', compact('constancia', 'rutaCerrar'));
    }
}
