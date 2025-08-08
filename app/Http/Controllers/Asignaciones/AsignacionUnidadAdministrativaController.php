<?php

namespace App\Http\Controllers;

use App\Models\CatalogoUnidadesAdministrativas;
use App\Models\CuentaPublica;
use App\Models\User;
use Illuminate\Http\Request;

class AsignacionUnidadAdministrativaController extends Controller
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
    public function index(Request $request)
    {
       
        $users = $this->setQuery($request)->paginate(20);
        return view('asignacionunidadadministrativa.index',compact('users','request'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        
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
        $cp_2021=null;

        return view('asignacionunidadadministrativa.form', compact('unidades','unidad_administrativa','cp_2021','user'));
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
        $request ['cp_2021']='X';
        $user->update($request->all());
        return redirect()->route('asignacionunidadadministrativa.index',$user);  
        // return view('asignacionunidadadministrativa.index');
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
        if($request->filled('estatus')&& $request->input('estatus') != 'Todas') {
            $query = $query->whereLike('estatus', $request->input('estatus'));
        }
        return $query;
    }

}
