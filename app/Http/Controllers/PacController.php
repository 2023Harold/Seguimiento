<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class PacController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('pacanalista.index', compact('request'));
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
    /**Analista */

    public function mot()
    {
        $preconstancia = reporte(1, 'Fiscalizacion/Seguimiento/Pac/Analista/mot', [], 'docx');       

        return response()->download($preconstancia);
        
    }

    public function fc()
    {
        $preconstancia = reporte(1, 'Fiscalizacion/Seguimiento/Pac/Analista/fc', [], 'docx');       

        return response()->download($preconstancia);
        
    }
    public function fccd()
    {
        $preconstancia = reporte(1, 'Fiscalizacion/Seguimiento/Pac/Analista/fccd', [], 'docx');       

        return response()->download($preconstancia);
        
    }

    /**Lider */

    public function ar()
    {
        $preconstancia = reporte(1, 'Fiscalizacion/Seguimiento/Pac/Lider/ar', [], 'docx');       

        return response()->download($preconstancia);
        
    }

    public function ofiaar()
    {
        $preconstancia = reporte(1, 'Fiscalizacion/Seguimiento/Pac/Lider/ofiaar', [], 'docx');       

        return response()->download($preconstancia);
        
    }
    public function ofaroics()
    {
        $preconstancia = reporte(1, 'Fiscalizacion/Seguimiento/Pac/Lider/ofar_oics', [], 'docx');       

        return response()->download($preconstancia);
        
    }
    public function ac()
    {
        $preconstancia = reporte(1, 'Fiscalizacion/Seguimiento/Pac/Lider/ac', [], 'docx');       

        return response()->download($preconstancia);
        
    }
    public function ofai()
    {
        $preconstancia = reporte(1, 'Fiscalizacion/Seguimiento/Pac/Lider/ofai', [], 'docx');       

        return response()->download($preconstancia);
        
    }
    public function ofroics()
    {
        $preconstancia = reporte(1, 'Fiscalizacion/Seguimiento/Pac/Lider/ofr_oics', [], 'docx');       

        return response()->download($preconstancia);
        
    }
    public function ofprasoics()
    {
        $preconstancia = reporte(1, 'Fiscalizacion/Seguimiento/Pac/Lider/ofpras_oics', [], 'docx');       

        return response()->download($preconstancia);
        
    }
    public function ofsc()
    {
        $preconstancia = reporte(1, 'Fiscalizacion/Seguimiento/Pac/Lider/ofsc', [], 'docx');       

        return response()->download($preconstancia);
        
    }
    public function ofuaj()
    {
        $preconstancia = reporte(1, 'Fiscalizacion/Seguimiento/Pac/Lider/ofuaj', [], 'docx');       

        return response()->download($preconstancia);
        
    }
    public function ac10()
    {
        $preconstancia = reporte(1, 'Fiscalizacion/Seguimiento/Pac/Lider/ac2', [], 'docx');       

        return response()->download($preconstancia);
        
    }
    public function acral()
    {
        $preconstancia = reporte(1, 'Fiscalizacion/Seguimiento/Pac/Lider/ac_ral', [], 'docx');       

        return response()->download($preconstancia);
        
    }
    public function ofac()
    {
        $preconstancia = reporte(1, 'Fiscalizacion/Seguimiento/Pac/Lider/ofac', [], 'docx');       

        return response()->download($preconstancia);
        
    }
    public function anv()
    {
        $preconstancia = reporte(1, 'Fiscalizacion/Seguimiento/Pac/Lider/anv', [], 'docx');       

        return response()->download($preconstancia);
        
    }
    public function ofanv()
    {
        $preconstancia = reporte(1, 'Fiscalizacion/Seguimiento/Pac/Lider/ofanv', [], 'docx');       

        return response()->download($preconstancia);
        
    }
    public function av()
    {
        $preconstancia = reporte(1, 'Fiscalizacion/Seguimiento/Pac/Lider/av', [], 'docx');       

        return response()->download($preconstancia);
        
    }
    public function oi()
    {
        $preconstancia = reporte(1, 'Fiscalizacion/Seguimiento/Pac/Lider/oi', [], 'docx');       

        return response()->download($preconstancia);
        
    }
    public function ofriii()
    {
        $preconstancia = reporte(1, 'Fiscalizacion/Seguimiento/Pac/Lider/ofriii', [], 'docx');       

        return response()->download($preconstancia);
        
    }
    public function ai()
    {
        $preconstancia = reporte(1, 'Fiscalizacion/Seguimiento/Pac/Lider/ai', [], 'docx');       

        return response()->download($preconstancia);
        
    }
    public function ofis()
    {
        $preconstancia = reporte(1, 'Fiscalizacion/Seguimiento/Pac/Jefe/ofis', [], 'docx');       

        return response()->download($preconstancia);
        
    }
    public function is()
    {
        $preconstancia = reporte(1, 'Fiscalizacion/Seguimiento/Pac/Jefe/is', [], 'docx');       

        return response()->download($preconstancia);
        
    }
    public function is2()
    {
        $preconstancia = reporte(1, 'Fiscalizacion/Seguimiento/Pac/Jefe/is2', [], 'docx');       

        return response()->download($preconstancia);
        
    }
    public function mda()
    {
        $preconstancia = reporte(1, 'Fiscalizacion/Seguimiento/Pac/Direccion/mda', [], 'docx');       

        return response()->download($preconstancia);
        
    }
    public function mdi()
    {
        $preconstancia = reporte(1, 'Fiscalizacion/Seguimiento/Pac/Direccion/mdj', [], 'docx');       

        return response()->download($preconstancia);
        
    }
    public function aa()
    {
        $preconstancia = reporte(1, 'Fiscalizacion/Seguimiento/Pac/Titular/aa', [], 'docx');       

        return response()->download($preconstancia);
        
    }
}
