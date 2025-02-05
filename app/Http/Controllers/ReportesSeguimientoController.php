<?php

namespace App\Http\Controllers;

use App\Exports\ReporteSeguimiento;
use App\Models\Auditoria;
use Illuminate\Http\Request;
use Excel;

class ReportesSeguimientoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     protected $model;

     public function __construct(Auditoria $model)
     {
         $this->model = $model;
     }

    public function index(Request $request)
    {       
        $auditorias = $this->setQuery($request)->orderBy('id')->paginate(30);                
        return view('reportesseg.index', compact('request','auditorias'));
        
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
        $auditorias = $this->model->orderBy('id')->paginate(30);

        return view('reportesseg.show', [
            'auditorias' => $auditorias
        ]);
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
    public function setQuery(Request $request)
    {
        $query = $this->model;
		$query = $query->where('cuenta_publica',getSession('cp'));
   

        return $query;
    }

    public function export() 
    {
        return Excel::download(new ReporteSeguimiento, 'invoices.xlsx');
    }

}
