<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class CedulaInicialPrimeraEtapaController extends Controller
{
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
    public function edit(Auditoria $auditoria)
    {
        $TSP=0;
        $TSPS=0;
        $TSPNS=0;

        foreach ($auditoria->totalsolacl as $solicitud) {
            $TSP=$TSP+$solicitud->monto_aclarar;
            $TSPS=$TSPS+$solicitud->solicitudesaclaracion->monto_solventado;
            $TSPNS=$TSPNS+($solicitud->monto_aclarar-$solicitud->solicitudesaclaracion->monto_solventado);
        }

        //dd($totalSolicitudesPromovidas,$totalSolicitudesPromovidasSolventadas,$totalSolicitudesPromovidasNoSolventadas);
        $TPP=0;
        $TPPS=0;
        $TPPNS=0;

        foreach ($auditoria->totalpliegos as $pliego) {
            $TPP=$TPP+$pliego->monto_aclarar;
            $TPPS=$TPPS+$pliego->pliegosobservacion->monto_solventado;
            $TPPNS=$TPPNS+($pliego->monto_aclarar-$pliego->pliegosobservacion->monto_solventado);
        }

        $TAP=$TSP+$TPP;
        $TAPS=$TSPS+$TPPS;
        $TAPNS=$TSPNS+$TPPNS;
            
        $pdf = Pdf::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadView('cedulageneral.show',compact('auditoria','TAP','TAPS','TAPNS','TSP','TSPS','TSPNS','TPP','TPPS','TPPNS'))->setPaper('a4', 'landscape')->stream('archivo.pdf');
        $nombre='CedulaGeneral'.str_replace("/", "_", $auditoria->numero_auditoria).'.pdf';
        $pdfgenrado = file_put_contents('storage/temporales/'.$nombre, $pdf);
        
        return view('cedulageneral.index',compact('nombre','auditoria'));
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
}