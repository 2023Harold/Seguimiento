<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\RolePermission;
use DB;

class AccesoController extends Controller
{
    protected $model;

    public function __construct(Permission $model)
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
        $roles = Role::all();
        $permisos = $this->setQuery($request);
        
        return view('accesos.index', compact('roles', 'permisos', 'request'));
    }

    private function setQuery($request)
    {
        $query = $this->model;
        $query = $query->where(DB::raw('lower(name)'), 'like', '%'.strtolower($request->permiso).'%');
        $query = $query->orderBy('id', 'desc')->paginate(20);
        return $query;
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
        /*$roles=Role::orderBy('id','DESC')->get();
        foreach ($roles as $rol) {
              $nombre_rol = str_replace(" ","_",$rol->name);
              $rol->syncPermissions($request[$nombre_rol]);
        }

        setMessage("Los permisos han sido actualizados");
        return redirect('/acceso');*/
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Acceso  $acceso
     * @return \Illuminate\Http\Response
     */
    public function show(Acceso $acceso)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Acceso  $acceso
     * @return \Illuminate\Http\Response
     */
    public function edit(Acceso $acceso)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Acceso  $acceso
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Acceso $acceso)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Acceso  $acceso
     * @return \Illuminate\Http\Response
     */
    public function destroy(Acceso $acceso)
    {
        //
    }

    public function setPermission(Request $request){
        $permiso = Permission::findOrFail($request->permiso);  
        $rol=Role::findOrFail($request->rol); 
        if ($request->estado==1) {
            if (!empty($permiso) && !empty($rol)) {
                $permiso->assignRole($rol);
                $msg = array("tipo" => 'success',
                            "msg" 	=> 'Permiso agregado correctamente.');
            }else{
                $msg = array("tipo" => 'danger',
                            "msg" 	=> 'Error en la petición, intente más tarde.');
            }
        }else{
            if (!empty($permiso) && !empty($rol)) {
                $permiso->removeRole($rol);
                $msg = array("tipo" => 'success',
                        "msg" 	=> 'Permiso revocado correctamente');
            }else{
                $msg = array("tipo" => 'danger',
                        "msg" 	=> 'Error en la petición, intente más tarde.');
            }
        }
        return response()->json($msg);
    }
}
