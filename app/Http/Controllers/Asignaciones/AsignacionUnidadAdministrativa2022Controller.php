<?php

namespace App\Http\Controllers;

use App\Models\CatalogoUnidadesAdministrativas;
use App\Models\User;
use Illuminate\Http\Request;

class AsignacionUnidadAdministrativa2022Controller extends Controller
{
    
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
    public function index()
    {
        //
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
    public function edit(User $user)
    {
        $unidades = CatalogoUnidadesAdministrativas::orderBy('id','DESC')->get() ->pluck('descripcion','id')->prepend('Seleccionar una opción', '');
        $unidad_administrativa ='Asignación';
        $cp_2022=null;
        return view('asignacionunidadadministrativa2022.form', compact('unidades','unidad_administrativa','cp_2022','user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,User $user)
    {        
        $request ['cp_2022']='X';
        $user->update($request->all());
        return redirect()->route('asignacionunidadadministrativa.index',$user);  
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
}
