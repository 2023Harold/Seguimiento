<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;

class QuickLoginController extends Controller
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
        if (App::environment('local')) {
            $usuarios = $this->setQuery($request)->get();
            Artisan::call('optimize:clear');

            return view('quicklogin.index', compact('usuarios'));
        }

        return abort(403);
    }

    protected function setQuery(Request $request)
    {
        $query = $this->model;

        if ($request->filled('nombre')) {
            $query = $query->where('name', 'like', '%'.$request->input('nombre').'%');
        }

        if ($request->filled('email')) {
            $query = $query->where('email', 'like', '%'.$request->input('email').'%');
        }

        if ($request->filled('inactivo') && $request->input('inactivo') != 'Todas') {
            if ($request->input('inactivo') == 'Inactivas') {
                $query = $query->where('estatus', 'Inactivo');
            }
            if ($request->input('inactivo') == 'Activas') {
                $query = $query->where('estatus', 'Activo');
            }
        }

        return $query;
    }

    public function loginas(Request $request)
    {
		$tamaño = strlen($request['usuario']);
        $id_sinp5 = substr($request['usuario'], 5, $tamaño);
        $id_sinu5 = str_replace(substr($id_sinp5, -5, -1), '', $id_sinp5);
        $id = intval(base64_decode($id_sinu5));
        $usuario = User::where('usuario_plataforma_id', $id)->first();

        if (App::environment('local')) {
            Auth::login($usuario);

            return redirect()->route('cphome');
        }

        return abort(403);      
    }
	
	public function loginasuser(User $usuario)
    {			
        if (App::environment('local')) {
            Auth::login($usuario);

            return redirect()->route('cphome');
        }

        return abort(403);      
    }
	
}
