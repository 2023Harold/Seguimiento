<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use App\Models\AuditoriaAccion;
use App\Models\CatalogoTipoAccion;
use App\Models\CatalogoTipoAuditoria;
use App\Models\Movimientos;
use App\Models\SUTIC\EntidadFiscalizableIntra;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class SeguimientoAuditoriaController extends Controller
{
    protected $model;

    public function __construct(Auditoria $model)
    {
        $this->model = $model;
    }

    public function index(Request $request)
    {
        $auditorias = $this->setQuery($request)->orderBy('id')->paginate(30);
               
        return view('seguimientoauditoria.index', compact('auditorias', 'request'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $ejercicios=[null=>'',2020=>2020,2021=>2021,2022=>2022];
        $auditoria = new Auditoria();
        $accion = 'Agregar';
        $auditorias=Auditoria::all()->count();
        $consecutivo=$auditorias+1;
        $entidades = EntidadFiscalizableIntra::where('NivEntFis', 1)->where('StsEntFis', 1)->whereNotIN('PkCveEntFis', [607, 608])->get()->pluck('NomEntFis', 'PkCveEntFis');
        $tipos = CatalogoTipoAuditoria::all()->pluck('descripcion', 'id')->prepend('Seleccionar una opción', '');      
        $tiporevision = [null=>'','Cumplimiento Financiero'=>'Cumplimiento Financiero','Inversión Física'=>'Inversión Física','Financiera'=>'Financiera','Obra'=>'Obra','Desempeño'=>'Desempeño'];
        $periodorevision= [null=>'','01 de Enero al 31 de Diciembre 2020'=>'01 de Enero al 31 de Diciembre 2020','01 de Enero al 31 de Diciembre 2021'=>'01 de Enero al 31 de Diciembre 2021','01 de Enero al 31 de Diciembre 2022'=>'01 de Enero al 31 de Diciembre 2022'];
        //$lideresProyecto=User::where('siglas_rol','LP')->where('unidad_administrativa_id',auth()->user()->director->unidad_administrativa_id)->get()->pluck('name','id')->prepend('Seleccionar una opción', '');
        $lideresProyecto=User::where('siglas_rol','LP')->where('unidad_administrativa_id',auth()->user()->jefe->unidad_administrativa_id)->get()->pluck('name','id')->prepend('Seleccionar una opción', '');
        $entidad1 = null;
        $entidad2 = null;
        $entidad3 = null;
        $cargosasociadosIntra = null;            

        return view('seguimientoauditoria.form', compact('ejercicios','auditoria','accion','consecutivo','entidades', 'entidad1', 'entidad2', 'entidad3','tipos','tiporevision','periodorevision','lideresProyecto'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->normalizarDatos($request);       
        mover_archivos($request, ['informe_auditoria'], null);
        $request['usuario_creacion_id'] = auth()->user()->id;
        $request['unidad_administrativa_registro']=auth()->user()->unidad_administrativa_id;
        $auditoria = Auditoria::create($request->all());
       
        setMessage('La auditoría se ha registrado correctamente.');

        return redirect()->route('seguimientoauditoria.acciones', $auditoria);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Auditoria  $auditoria
     * @return \Illuminate\Http\Response
     */
    public function show(Auditoria $auditoria)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Auditoria  $auditoria
     * @return \Illuminate\Http\Response
     */
    public function edit(Auditoria $auditoria)
    {       
        
        $accion = 'Editar';      
        $entidades = EntidadFiscalizableIntra::where('NivEntFis', 1)->where('StsEntFis', 1)->whereNotIN('PkCveEntFis', [607, 608])->get()->pluck('NomEntFis', 'PkCveEntFis');
        $tipos = CatalogoTipoAuditoria::all()->pluck('descripcion', 'id')->prepend('Seleccionar una opción', '');      
        $tiporevision = [null=>'','Cumplimiento Financiero'=>'Cumplimiento Financiero','Inversión Física'=>'Inversión Física','Financiera'=>'Financiera','Obra'=>'Obra','Desempeño'=>'Desempeño'];
        $periodorevision= [null=>'','01 de Enero al 31 de Diciembre 2020'=>'01 de Enero al 31 de Diciembre 2020','01 de Enero al 31 de Diciembre 2021'=>'01 de Enero al 31 de Diciembre 2021','01 de Enero al 31 de Diciembre 2022'=>'01 de Enero al 31 de Diciembre 2022'];
        $lideresProyecto=User::where('siglas_rol','LP')->where('unidad_administrativa_id',auth()->user()->jefe->unidad_administrativa_id)->get()->pluck('name','id');
        $entidad1 = null;
        $entidades2 = null;
        $entidad2 = null;
        $entidades3 = null;
        $entidad3 = null;

        $entidadFiscalizable = EntidadFiscalizableIntra::find($auditoria->entidad_fiscalizable_id);
            
        if ($entidadFiscalizable->NivEntFis == 3) {
            $entidad3 = $entidadFiscalizable->PkCveEntFis;
            $entidades3 = EntidadFiscalizableIntra::where('NivEntFis', 3)->where('FkCveEntFis', $entidadFiscalizable->FkCveEntFis)->where('StsEntFis', 1)->get()->pluck('NomEntFis', 'PkCveEntFis');

            $entidadFiscalizable2 = EntidadFiscalizableIntra::find($entidadFiscalizable->FkCveEntFis);
            $entidad2 = $entidadFiscalizable2->PkCveEntFis;              
            $entidades2 = EntidadFiscalizableIntra::where('NivEntFis', 2)->where('FkCveEntFis', $entidadFiscalizable2->FkCveEntFis)->where('StsEntFis', 1)->get()->pluck('NomEntFis', 'PkCveEntFis');

            $entidadFiscalizable1 = EntidadFiscalizableIntra::find($entidadFiscalizable2->FkCveEntFis);
            $entidad1 = empty($entidadFiscalizable1->PkCveEntFis)?'':$entidadFiscalizable1->PkCveEntFis;
        }
        if ($entidadFiscalizable->NivEntFis == 2) {
            $entidad2 = $entidadFiscalizable->PkCveEntFis;
            $entidades2 = EntidadFiscalizableIntra::where('NivEntFis', 2)->where('FkCveEntFis', $entidadFiscalizable->FkCveEntFis)->where('StsEntFis', 1);
            if ($entidadFiscalizable->FkCveEntFis == 611) {
                    $entidades2 = $entidades2->whereNotNull('CveEntFis');
                }
                $entidades2 = $entidades2->get()->pluck('NomEntFis', 'PkCveEntFis');              

                $entidadFiscalizable1 = EntidadFiscalizableIntra::find($entidadFiscalizable->FkCveEntFis);
                $entidad1 = $entidadFiscalizable1->PkCveEntFis;
            }
            if ($entidadFiscalizable->NivEntFis == 1) {
                $entidad1 = $entidadFiscalizable->PkCveEntFis;
            }

        return view('seguimientoauditoria.form', compact('auditoria', 'accion','entidades', 'entidades2', 'entidades3', 'entidad1', 'entidad2', 'entidad3','tipos','tiporevision','periodorevision','lideresProyecto'));        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Auditoria  $auditoria
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Auditoria $auditoria)
    {
        $this->normalizarDatos($request);       
        mover_archivos($request, ['informe_auditoria'], $auditoria);
        $auditoria->update($request->all());
       
        setMessage('La auditoría se ha modificado correctamente.');

        return redirect()->route('seguimientoauditoria.acciones', $auditoria);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Auditoria  $auditoria
     * @return \Illuminate\Http\Response
     */
    public function destroy(Auditoria $auditoria)
    {
        //
    }

    public function setQuery(Request $request)
    {
         $query = $this->model;

         
        if(in_array("Analista", auth()->user()->getRoleNames()->toArray())){           
            $query = $query->where('usuario_creacion_id',auth()->id());
        }

        if(in_array("Lider de Proyecto", auth()->user()->getRoleNames()->toArray())){    
            $userLider=auth()->user(); 
            $query = $query->whereRaw('LOWER(lider_proyecto_id) LIKE (?) ',["%{$userLider->id}%"])->whereNotNull('fase_autorizacion');
        }       

        if(in_array("Jefe de Departamento de Seguimiento", auth()->user()->getRoleNames()->toArray())){     
            $unidadAdministrativa=auth()->user()->unidad_administrativa_id;
            $query = $query->whereNotNull('fase_autorizacion')->whereRaw('LOWER(unidad_administrativa_registro) LIKE (?) ',["%{$unidadAdministrativa}%"])->whereNotNull('nivel_autorizacion');
        }

        if(in_array("Director de Seguimiento", auth()->user()->getRoleNames()->toArray())||
           in_array("Titular Unidad de Seguimiento", auth()->user()->getRoleNames()->toArray())||
           in_array("Administrador del Sistema", auth()->user()->getRoleNames()->toArray())||
           in_array("Auditor Superior", auth()->user()->getRoleNames()->toArray())){                 
            $unidadAdministrativa=rtrim(auth()->user()->unidad_administrativa_id, 0);			
            $query = $query->whereNotNull('fase_autorizacion')->whereRaw('LOWER(unidad_administrativa_registro) LIKE (?) ',["%{$unidadAdministrativa}%"]);
        }
        
                
        if ($request->filled('numero_auditoria')) {
             $numeroAuditoria=strtolower($request->numero_auditoria);
             $query = $query->whereRaw('LOWER(numero_auditoria) LIKE (?) ',["%{$numeroAuditoria}%"]);
         }

        if ($request->filled('entidad_fiscalizable')) {
            $entidadFiscalizable=strtolower($request->entidad_fiscalizable);
            $query = $query->whereRaw('LOWER(entidad_fiscalizable) LIKE (?) ',["%{$entidadFiscalizable}%"]);
        }

        if ($request->filled('acto_fiscalizacion')) {
            $actoFiscalizacion=strtolower($request->acto_fiscalizacion);
            $query = $query->whereRaw('LOWER(acto_fiscalizacion) LIKE (?) ',["%{$actoFiscalizacion}%"]);
        }

        return $query;
    }

    public function accionesConsulta(Auditoria $auditoria)
    {
        $movimiento='consultar';       
        $acciones = AuditoriaAccion::where('segauditoria_id',$auditoria->id)->paginate(30);   
        $request = new Request(); 
        $tiposaccion= CatalogoTipoAccion::all()->pluck('descripcion', 'id')->prepend('Todas', 0);     
       
        return view('seguimientoauditoriaaccion.index', compact('acciones', 'request', 'auditoria','movimiento','tiposaccion'));
    }

    public function getCargosAsociados(Request $request)
    {
        $datos = [];
        $entidades = [];
        $cargosasociados = [];

        $entidadselecionada = EntidadFiscalizableIntra::where('PkCveEntFis', $request->entidadid)->first();
        $entidadesIntra = EntidadFiscalizableIntra::where('FkCveEntFis', $request->entidadid)->where('StsEntFis', 1);

        if ($request->entidadid == 611) {
            $entidadesIntra = $entidadesIntra->whereNotNull('CveEntFis');
        }
        $entidadesIntra = $entidadesIntra->get();

        if (!empty($entidadesIntra) && count($entidadesIntra) > 0) {
            foreach ($entidadesIntra as $entidadIntra) {
                $entidades[] = ['id' => $entidadIntra->PkCveEntFis, 'text' => $entidadIntra->NomEntFis];
            }
        }       

        $datos[1] = $entidades;

        return response()->json($datos);
    }

    public function normalizarDatos(Request $request)
    {
       $entidad=EntidadFiscalizableIntra::where('PkCveEntFis',$request->entidad_fiscalizable_id)->first();
       $tipoauditoria=CatalogoTipoAuditoria::find($request->tipo_auditoria_id);   
       $entidadCompleta='';

        if ($entidad->NivEntFis == 3){
            $entidadCompleta=$entidad->entidadFiscalizableN1->NomEntFis.' - '.$entidad->entidadFiscalizableN2->NomEntFis.' - '.$entidad->NomEntFis;    
        }elseif ($entidad->NivEntFis == 2){                
            $entidadCompleta=$entidad->entidadFiscalizableN2->NomEntFis.' - '.$entidad->NomEntFis; 
        }elseif ($entidad->NivEntFis == 1){
            $entidadCompleta=$entidad->NomEntFis;    
        }
       
       $request['entidad_fiscalizable'] = $entidadCompleta;
       $request['tipo_entidad']=$entidad->Ambito;
       $request['siglas_entidad']=$entidad->SigEntFis;
       $request['ejercicio']=0;
       $request['acto_fiscalizacion']=$tipoauditoria->descripcion;       

       return  $request;
    }

    public function auditoriaAcciones(Auditoria $auditoria)
    {
        setSession('auditoria_id',$auditoria->id);
        
        return  redirect()->route('seguimientoauditoriaacciones.index');
    }

    public function concluir(Auditoria $auditoria)
    {
        Movimientos::create([
            'tipo_movimiento' => 'Registro de la auditoría',
            'accion' => 'Registro de la auditoría',
            'accion_id' => $auditoria->id,
            'estatus' => 'Aprobado',
            'usuario_creacion_id' => auth()->id(),
            'usuario_asignado_id' => auth()->id(),
        ]);

        if (strlen($auditoria->nivel_autorizacion) == 3 || strlen($auditoria->nivel_autorizacion) == 4) {
            $nivel_autorizacion = $auditoria->nivel_autorizacion;
        } else {
            $nivel_autorizacion = substr(auth()->user()->unidad_administrativa_id, 0, 5);
        }
       

        $auditoria->update(['registro_concluido' => 'Si', 'fase_autorizacion' => 'En revisión 01']);        

        $titulo = 'Registro de auditoría';
        $mensaje = '<strong>Estimado (a) ' . $auditoria->lider->name . ', ' . $auditoria->lider->puesto . ':</strong><br>
                    Ha sido registrada la auditoría No. ' . $auditoria->numero_auditoria . ', por parte del Analista ' . 
                    auth()->user()->name . ', por lo que se requiere realice la revisión oportuna del la auditoría.';
        auth()->user()->insertNotificacion($titulo, $mensaje, now(), $auditoria->lider->unidad_administrativa_id,$auditoria->lider->id);

        setMessage('El registro auditoría se ha concluido y enviado a revisión.');


             
        return  redirect()->route('seguimientoauditoria.index');
    }
}
