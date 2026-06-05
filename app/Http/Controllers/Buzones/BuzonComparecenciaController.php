<?php

namespace App\Http\Controllers\Buzones;

use App\Http\Controllers\Controller;
use App\Models\CatalogoUnidadesAdministrativas;
use App\Models\Movimientos;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Auditoria;
use PhpParser\Node\Stmt\Else_;


class BuzonComparecenciaController extends Controller
{
    protected $model;

    public function __construct(Auditoria $model)
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
        //dd("index, BuzonSeguimientoAccionesController");
        $auditorias = $this->setQuery($request)->orderByRaw("TO_NUMBER(REGEXP_SUBSTR(numero_auditoria, '\\d+'))");
        $auditorias = $auditorias->paginate(30);

        return view('Buzones.buzoncomparecencia.index', compact('auditorias', 'request',));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, )
    {
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $auditoria)
    {
        $partes = explode('-', $auditoria);
        $clavesaud = explode('-', $partes[0]);
        //dd($partes,$clavesaud);
        if($clavesaud[0]=='AUD'){
            $auditoria = Auditoria::find($partes[1]);
            setSession('auditoria_id',$auditoria->id);
            setSession('auditoriacp_id',$auditoria->id);
            //dd("Auditoria",$auditoria);
            return redirect()->route('auditoriaseguimiento.index',);
        }elseif($clavesaud[0]=='COM'){
            $auditoria = Auditoria::find($partes[1]);
            setSession('auditoria_id',$auditoria->id);
            setSession('auditoriacp_id',$auditoria->id);
            //dd("Auditoria",$auditoria);
            return redirect()->route('comparecenciaacta.index',);
        }
        dd("show",);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit()
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
    public function update(Request $request)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        dd("destroy");

    }
    
    public function setQuery(Request $request)
    {
        $query = $this->model;
        $query = $query->where('cuenta_publica', getSession('cp'));

        // STAFF — fix del bug del nuevo
        if (in_array("Staff Juridico", auth()->user()->getRoleNames()->toArray())) {
            $query = $query->whereHas('auditoriausuarios', function ($q) {
                $q->where("staff_id", auth()->user()->id);
            });
        }

        // JEFE DE DEPARTAMENTO
        if (in_array("Jefe de Departamento de Seguimiento", auth()->user()->getRoleNames()->toArray())) {
            if (getSession('cp') != 2023 || getSession('cp') != 2024) {
                $query = $query->where(function ($queryJDE) {
                    $queryJDE->where('departamento_encargado_id', auth()->user()->unidad_administrativa_id)
                        ->orWhere(function ($queryJDA) {
                            $queryJDA->whereHas('acciones', function ($q) {
                                $unidadAdministrativa = auth()->user()->unidad_administrativa_id;
                                $q->whereNotNull('segauditorias.fase_autorizacion')
                                ->whereRaw('LOWER(departamento_asignado_id) LIKE (?)', ["%{$unidadAdministrativa}%"])
                                ->whereNotNull('segauditorias.nivel_autorizacion');
                            });
                        });
                });
            } else {
                $query = $query->where(function ($queryJDE) {
                    $queryJDE->where('departamento_encargado_id', auth()->user()->cp_ua2023 || auth()->user()->cp_ua2024);
                });
            }
        }

        // LIDER Y ANALISTA — con lógica de equipo de trabajo
        if (!usaEquipoTrabajo()) {
            if (getSession('cp') == 2022) {
                $query = $query->whereHas('acciones', function ($q) {
                    if (in_array("Analista", auth()->user()->getRoleNames()->toArray())) {
                        $q->where('analista_asignado_id', auth()->user()->id);
                    }
                    if (in_array("Lider de Proyecto", auth()->user()->getRoleNames()->toArray())) {
                        $userLider = auth()->user();
                        $q->whereRaw('LOWER(lider_asignado_id) LIKE (?)', ["%{$userLider->id}%"])
                        ->whereNotNull('segauditorias.fase_autorizacion');
                    }
                });
            } else {
                if (in_array("Analista", auth()->user()->getRoleNames()->toArray())) {
                    $query = $query->where('analistacp_id', auth()->user()->id);
                }
                if (in_array("Lider de Proyecto", auth()->user()->getRoleNames()->toArray())) {
                    $query = $query->where('lidercp_id', auth()->user()->id);
                }
            }
        } else {
            if (in_array("Analista", auth()->user()->getRoleNames()->toArray())) {
                $query = $query->whereHas('auditoriausuarios', function ($q) {
                    $q->where('user_id', auth()->user()->id)
                    ->where('rol_code', 'Analista')
                    ->where('estatus', 'Activo');
                });
            }
            if (in_array("Lider de Proyecto", auth()->user()->getRoleNames()->toArray())) {
                $query = $query->whereHas('auditoriausuarios', function ($q) {
                    $q->where('user_id', auth()->user()->id)
                    ->where('rol_code', 'Lider')
                    ->where('estatus', 'Activo');
                });
            }
        }

        // DIRECTOR
        if (in_array("Director de Seguimiento", auth()->user()->getRoleNames()->toArray())) {
            $unidadAdministrativa = auth()->user()->unidad_administrativa_id;
            $query = $query->whereRaw('LOWER(direccion_asignada_id) LIKE (?)', ["%{$unidadAdministrativa}%"]);
        }

        // TITULAR / ADMIN / AUDITOR SUPERIOR
        if (in_array("Titular Unidad de Seguimiento", auth()->user()->getRoleNames()->toArray()) ||
            in_array("Administrador del Sistema", auth()->user()->getRoleNames()->toArray()) ||
            in_array("Auditor Superior", auth()->user()->getRoleNames()->toArray())) {
            $query = $query->whereNotNull('fase_autorizacion')->where('fase_autorizacion', 'Autorizado');
        }

        // UC
        if (auth()->user()->siglas_rol == 'UC') {
            $unidadAdministrativa = auth()->user()->unidad_administrativa_id;
            $query = $query->whereRaw('LOWER(direccion_asignada_id) LIKE (?)', ["%{$unidadAdministrativa}%"]);
        }

        // FILTROS DE BÚSQUEDA
        if ($request->filled('numero_auditoria')) {
            $numeroAuditoria = strtolower($request->numero_auditoria);
            $query = $query->whereRaw('LOWER(numero_auditoria) LIKE (?)', ["%{$numeroAuditoria}%"]);
        }
        if ($request->filled('entidad_fiscalizable')) {
            $entidadFiscalizable = strtolower($request->entidad_fiscalizable);
            $query = $query->whereRaw('LOWER(entidad_fiscalizable) LIKE (?)', ["%{$entidadFiscalizable}%"]);
        }
        if ($request->filled('acto_fiscalizacion')) {
            $actoFiscalizacion = strtolower($request->acto_fiscalizacion);
            $query = $query->whereRaw('LOWER(acto_fiscalizacion) LIKE (?)', ["%{$actoFiscalizacion}%"]);
        }
        if ($request->filled('direccionaud') && $request->input('direccionaud') != 'Todas') {
            $query = $query->whereRaw('LOWER(direccion_asignada_id) LIKE (?)', ["%{$request->input('direccionaud')}%"]);
        }
        if ($request->filled('departamentoasig')) {
            $query = $query->whereRaw('LOWER(departamento_encargado_id) LIKE (?)', ["%{$request->input('departamentoasig')}%"]);
        }

        // FILTROS LIDER/ANALISTA en búsqueda
        if (!usaEquipoTrabajo()) {
            if ($request->filled('liderasig')) {
                $query = $query->whereRaw('LOWER(lidercp_id) LIKE (?)', ["%{$request->input('liderasig')}%"]);
            }
            if ($request->filled('analistaasig')) {
                $query = $query->whereRaw('LOWER(analistacp_id) LIKE (?)', ["%{$request->input('analistaasig')}%"]);
            }
        } else {
            if ($request->filled('liderasig')) {
                $query = $query->whereHas('auditoriausuarios', function ($q) use ($request) {
                    $q->where('user_id', $request->input('liderasig'))
                    ->where('rol_code', 'Lider')
                    ->where('estatus', 'Activo');
                });
            }
            if ($request->filled('analistaasig')) {
                $query = $query->whereHas('auditoriausuarios', function ($q) use ($request) {
                    $q->where('user_id', $request->input('analistaasig'))
                    ->where('rol_code', 'Analista')
                    ->where('estatus', 'Activo');
                });
            }
        }

        // APARTADO + ESTATUS (del viejo, solo si el controller lo necesita)
        $apartado = $request->input('apartado');
        $status   = $request->filled('estatus') ? mb_strtolower($request->input('estatus'), 'UTF-8') : null;

        if ($apartado) {
            switch ($apartado) {
                case 'radicacion':
                    $query->whereHas('radicacion', function ($q) use ($status) {
                        if ($status) $q->whereRaw('LOWER(fase_autorizacion) = ?', [$status]);
                    });
                    break;
                case 'comparecencia':
                    $query->whereHas('comparecencia', function ($q) use ($status) {
                        if ($status) $q->whereRaw('LOWER(fase_autorizacion) = ?', [$status]);
                    });
                    break;
                case 'informes':
                    $query->where(function ($q) use ($status) {
                        $q->whereHas('informeprimeraetapa', function ($qi) use ($status) {
                            if ($status) $qi->whereRaw('LOWER(fase_autorizacion) = ?', [$status]);
                        })->orWhereHas('informepliegos', function ($qp) use ($status) {
                            if ($status) $qp->whereRaw('LOWER(fase_autorizacion) = ?', [$status]);
                        });
                    });
                    break;
                case 'acuerdo':
                    $query->where(function ($q) use ($status) {
                        $q->whereHas('acuerdoconclusion', function ($qa) use ($status) {
                            if ($status) $qa->whereRaw('LOWER(fase_autorizacion) = ?', [$status]);
                        })->orWhereHas('acuerdoconclusionpliegos', function ($qap) use ($status) {
                            if ($status) $qap->whereRaw('LOWER(fase_autorizacion) = ?', [$status]);
                        });
                    });
                    break;
                case 'turno_oic':
                    $query->whereHas('turnooic', function ($q) use ($status) {
                        if ($status) $q->whereRaw('LOWER(fase_autorizacion) = ?', [$status]);
                    });
                    break;
                case 'turno_ui':
                    $query->whereHas('turnoui', function ($q) use ($status) {
                        if ($status) $q->whereRaw('LOWER(fase_autorizacion) = ?', [$status]);
                    });
                    break;
                case 'turno_archivo':
                    $query->whereHas('turnoarchivo', function ($q) use ($status) {
                        if ($status) $q->whereRaw('LOWER(fase_autorizacion) = ?', [$status]);
                    });
                    break;
            }
        } elseif ($status) {
            $query->whereRaw('LOWER(fase_autorizacion) = ?', [$status]);
        }

        return $query;
    }
}
