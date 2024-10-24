<?php

namespace App\Http\Controllers;

use App\Models\CuentaPublica;
use Illuminate\Http\Request;

class CuentaPublicaHomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
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
        $cps=CuentaPublica::all();
        return view('cphome',compact('cps'));
    }
}
