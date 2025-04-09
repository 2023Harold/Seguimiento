<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FolioCrr;
use App\Models\Auditoria;

class FolioCRRController extends Controller
{
    protected $model;

    public function __construct(FolioCrr $model)
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
        $folios = $this->setQuery($request)->paginate(25);
        $auditoria = Auditoria::find(getSession('auditoria_id'));

        return view('folios.index', compact('auditoria','folios','request'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $folio = new FolioCrr();
        $auditoria = Auditoria::find(getSession('auditoria_id'));

        return view('folios.form', compact('auditoria','folio'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request['auditoria_id'] =getSession('auditoria_id');
        $request['usuario_creacion_id'] = auth()->id();
        mover_archivos($request, ['oficio_contestacion_general']);
        $folio  = FolioCrr::create($request->all());

        setMessage('El folio ha sido agregado');
        return redirect()->route('folioscrr.index');
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
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
        $query = $query->where('auditoria_id',getSession('auditoria_id'));
        $query = $query->orderBy('id','DESC');

        return $query;
    }
}
