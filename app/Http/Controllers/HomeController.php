<?php

namespace App\Http\Controllers;

use App\Models\CuentaPublica;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }
    public function cuenta(CuentaPublica $cp)
    {
        //setSession('cp'$cp-id);
        //setSession('cp_ua'$);


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

        
        return redirect('/home');
    }
}
