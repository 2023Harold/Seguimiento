<?php

namespace App\Exports;

use App\Models\Auditoria;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ReporteSeguimiento implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        $auditorias = Auditoria::all();
        return view('reportesseg.show', [
            'auditorias' => $auditorias
        ]);
    }
}
