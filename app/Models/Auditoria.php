<?php

namespace App\Models;

use App\Models\SUTIC\EntidadFiscalizableIntra;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Propaganistas\LaravelFakeId\RoutesWithFakeIds;
use Spatie\Permission\Traits\HasRoles;

class Auditoria extends Model
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, RoutesWithFakeIds;


            protected $table = 'segauditorias';
            /**
             * The attributes that are mass assignable.
             *
             * @var array<int, string>
             */
            protected $fillable = [
                'numero_auditoria',
                'numero_orden',
                'entidad_fiscalizable_id',
                'entidad_fiscalizable',
                'tipo_entidad',
                'siglas_entidad',
                'ejercicio',
                'periodo_revision',
                'tipo_auditoria_id',
                'acto_fiscalizacion',
                'informe_auditoria',
                'fojas_utiles',
                'registro_concluido',
                'constancia',
                'fase_autorizacion',
                'nivel_autorizacion',
                'unidad_administrativa_registro',
                'lider_proyecto_id',
                'usuario_creacion_id',
                'usuario_actualizacion_id',
                'direccion_asignada_id',
                'direccion_asignada',
                'reasignacion_direccion',
                'asignacion_departamentos',
                'asignacion_lider_analista',
                'departamento_encargado',
                'departamento_encargado_id',
                'entidad_descripcion',
                'tipologia_id',
                'tipologia',
                'created_at',
                'updated_at',
                'cuenta_publica',
            ];


            /**
             * The attributes that should be cast.
             *
             * @var array<string, string>
             */
            protected $casts = [
                'created_at'=>'datetime',
                'updated_at'=>'datetime'
            ];


            public function acciones()
            {
                return $this->hasMany(AuditoriaAccion::class, 'segauditoria_id', 'id')->whereNull('eliminado')->orderBy('consecutivo');
            }

            public function accionessinenvio()
            {
                return $this->hasMany(AuditoriaAccion::class, 'segauditoria_id', 'id')->whereNull('eliminado')->whereNull('fase_revision')->orderBy('consecutivo');
            }

            public function accionesall()
            {
                return $this->belongsTo(AuditoriaAccion::class, 'id','segauditoria_id');
            }

            public function accionesrecomendaciones()
            {
                return $this->hasMany(AuditoriaAccion::class, 'segauditoria_id', 'id')->whereNull('eliminado')->where('segtipo_accion_id',2)->orderBy('consecutivo');
            }

            public function accionesrecomendacionesautorizadas()
            {
                return $this->hasMany(AuditoriaAccion::class, 'segauditoria_id', 'id')->whereNull('eliminado')->where('segtipo_accion_id',2)
                ->whereHas('recomendaciones', function (Builder $query) {
                    $query->where('fase_autorizacion','Autorizado');
                })->orderBy('consecutivo');
            }

            public function accionespras()
            {
                return $this->hasMany(AuditoriaAccion::class, 'segauditoria_id', 'id')->whereNull('eliminado')->where('segtipo_accion_id',4)->orderBy('consecutivo');
            }
            public function accionesprasautorizadas()
            {
                return $this->hasMany(AuditoriaAccion::class, 'segauditoria_id', 'id')->whereNull('eliminado')->where('segtipo_accion_id',4)
                ->whereHas('pras', function (Builder $query) {
                    $query->where('fase_autorizacion','Autorizado');
                })->orderBy('consecutivo');
            }
			
			 public function accionespo()
            {
                return $this->hasMany(AuditoriaAccion::class, 'segauditoria_id', 'id')->whereNull('eliminado')->where('segtipo_accion_id',3)->orderBy('consecutivo');
            }
			
            public function accionespoautorizadas()
            {
                return $this->hasMany(AuditoriaAccion::class, 'segauditoria_id', 'id')->whereNull('eliminado')->where('segtipo_accion_id',3)
                ->whereHas('pliegosobservacion', function (Builder $query) {
                    $query->where('fase_autorizacion','Autorizado');
                })->orderBy('consecutivo');
            }
			
			public function accionessolacl()
            {
                return $this->hasMany(AuditoriaAccion::class, 'segauditoria_id', 'id')->whereNull('eliminado')->where('segtipo_accion_id',1)->orderBy('consecutivo');
            }
			
			public function accionessolaclpo()
            {
                return $this->hasMany(AuditoriaAccion::class, 'segauditoria_id', 'id')->whereNull('eliminado')->whereIn('segtipo_accion_id',[1,3])->orderBy('consecutivo');
            }
			
            public function accionessolaclautorizadas()
            {
                return $this->hasMany(AuditoriaAccion::class, 'segauditoria_id', 'id')->whereNull('eliminado')->where('segtipo_accion_id',1)
                ->whereHas('solicitudesaclaracion', function (Builder $query) {
                    $query->where('fase_autorizacion','Autorizado');
                })->orderBy('consecutivo');
            }
           

            public function accionesDepartamento()
            {
                return $this->hasMany(AuditoriaAccion::class, 'segauditoria_id', 'id')->where('departamento_asignado_id',substr(auth()->user()->unidad_administrativa_id, 0, 5).'0')->orderBy('consecutivo');
            }

            public function radicacion()
            {
                return $this->belongsTo(Radicacion::class, 'id', 'auditoria_id');
            }

            public function comparecencia()
            {
                return $this->belongsTo(Comparecencia::class, 'id', 'auditoria_id');
            }

            public function total()
            {
                return $this->hasMany(AuditoriaAccion::class, 'segauditoria_id', 'id')->whereNull('eliminado')->sum('monto_aclarar');
            }

            public function entidadFiscalizable()
            {
                return $this->hasOne(EntidadFiscalizableIntra::class, 'PkCveEntFis', 'entidad_fiscalizable_id');
            }

            public function movimientos()
            {
                return $this->hasMany(Movimientos::class, 'accion_id', 'id')->where('accion', 'Registro de la auditoría')->orderBy('id', 'ASC');
            }

            public function usuarioCreacion()
            {
                return $this->belongsTo(User::class, 'usuario_creacion_id', 'id');
            }
            public function lider()
            {
                return $this->belongsTo(User::class, 'lider_proyecto_id', 'id')->where('siglas_rol','LP');
            }

            public function getDirectorasignadoAttribute()
            {
                return User::where('unidad_administrativa_id',$this->direccion_asignada_id)->first();
            }

            public function getJefedepartamentoencargadoAttribute()
            {
                return User::where('unidad_administrativa_id',$this->departamento_encargado_id)->where('siglas_rol','JD')->first();
            }

            public function totalrecomendacion()
            {
                return $this->hasMany(AuditoriaAccion::class, 'segauditoria_id', 'id')->whereNull('eliminado')->where('segtipo_accion_id', 2);
            }

            public function totalpras()
            {
                return $this->hasMany(AuditoriaAccion::class, 'segauditoria_id', 'id')->whereNull('eliminado')->where('segtipo_accion_id', 4);
            }

            public function totalsolacl()
            {
                return $this->hasMany(AuditoriaAccion::class, 'segauditoria_id', 'id')->whereNull('eliminado')->where('segtipo_accion_id', 1);
            }

            public function totalpliegos()
            {
                return $this->hasMany(AuditoriaAccion::class, 'segauditoria_id', 'id')->whereNull('eliminado')->where('segtipo_accion_id', 3);
            }

            public function totalsolventadorecomendacion()
            {
                return $this->hasMany(AuditoriaAccion::class, 'segauditoria_id', 'id')->whereNull('eliminado')->where('segtipo_accion_id', 2)
                            ->whereHas('recomendaciones', function (Builder $query) {
                                $query->where('calificacion_sugerida','Atendida');
                            });
            }

            public function totalsolventadopras()
            {
                return $this->hasMany(AuditoriaAccion::class, 'segauditoria_id', 'id')->whereNull('eliminado')->where('segtipo_accion_id', 4)
                            ->whereHas('pras', function (Builder $query) {
                                $query->whereNotNull('constancia_turno');
                            });
            }

            public function totalsolventadosolacl()
            {
                return $this->hasMany(AuditoriaAccion::class, 'segauditoria_id', 'id')->whereNull('eliminado')->where('segtipo_accion_id', 1)
                            ->whereHas('solicitudesaclaracion', function (Builder $query) {
                                $query->where('calificacion_sugerida', 'Solventada');
                            });
            }

            public function totalsolventadopliegos()
            {
                return $this->hasMany(AuditoriaAccion::class, 'segauditoria_id', 'id')->whereNull('eliminado')->where('segtipo_accion_id', 3)
                            ->whereHas('pliegosobservacion', function (Builder $query) {
                                $query->where('calificacion_sugerida', 'Solventado');
                            });
            }

            public function totalNOsolventadorecomendacion()
            {
                return $this->hasMany(AuditoriaAccion::class, 'segauditoria_id', 'id')->whereNull('eliminado')->where('segtipo_accion_id', 2)
                            ->whereHas('recomendaciones', function (Builder $query) {
                                $query->whereNotIn('calificacion_sugerida',['Atendida']);
                            });
            }

            public function totalNOsolventadopras()
            {
                return $this->hasMany(AuditoriaAccion::class, 'segauditoria_id', 'id')->whereNull('eliminado')->where('segtipo_accion_id', 4)
                            ->whereHas('pras', function (Builder $query) {
                                $query->whereNull('constancia_turno');
                            });
            }

            public function totalNOsolventadosolacl()
            {
                return $this->hasMany(AuditoriaAccion::class, 'segauditoria_id', 'id')->whereNull('eliminado')->where('segtipo_accion_id', 1)
                            ->whereHas('solicitudesaclaracion', function (Builder $query) {
                                $query->whereNotIn('calificacion_sugerida', ['Solventada']);
                            });
            }

            public function totalNOsolventadopliegos()
            {
                return $this->hasMany(AuditoriaAccion::class, 'segauditoria_id', 'id')->whereNull('eliminado')->where('segtipo_accion_id', 3)
                            ->whereHas('pliegosobservacion', function (Builder $query) {
                                $query->whereNotIn('calificacion_sugerida', ['Solventado']);
                            });
            }

            public function accionesrevisadaslider()
            {
                return $this->hasMany(AuditoriaAccion::class, 'segauditoria_id', 'id')->whereNull('eliminado')->whereNotNull('revision_lider');
            }

            public function accionesrechazadaslider()
            {
                return $this->hasMany(AuditoriaAccion::class, 'segauditoria_id', 'id')->whereNull('eliminado')->whereNotNull('revision_lider')->where('revision_lider','Rechazado');
            }

            public function accionesrevisadasjefe()
            {
                return $this->hasMany(AuditoriaAccion::class, 'segauditoria_id', 'id')->whereNull('eliminado')->whereNotNull('revision_jefe');
            }

            public function accionesrechazadasjefe()
            {
                return $this->hasMany(AuditoriaAccion::class, 'segauditoria_id', 'id')->whereNull('eliminado')->whereNotNull('revision_jefe')->where('revision_jefe','Rechazado');
            }

            public function cedulageneralseguimiento()
            {
                return $this->hasMany(Cedula::class, 'auditoria_id', 'id')->where('cedula_tipo','Cedula General Seguimiento');
            }

            public function cedulageneralrecomendaciones()
            {
                return $this->hasMany(Cedula::class, 'auditoria_id', 'id')->where('cedula_tipo','Cedula General Recomendación');
            }

            public function cedulageneralpras()
            {
                return $this->hasMany(Cedula::class, 'auditoria_id', 'id')->where('cedula_tipo','Cedula General PRAS');
            }

            public function cedulaanalitica()
            {                       
                return $this->hasMany(Cedula::class, 'auditoria_id', 'id')->where('cedula_tipo','Cedula Analítica');
            }

            public function cedulaanaliticadesemp()
            {                       
                return $this->hasMany(Cedula::class, 'auditoria_id', 'id')->where('cedula_tipo','Cedula Analítica Desempeño');
            }

            public function movimientosCedulaGeneral()
            {
                return $this->hasMany(Movimientos::class, 'accion_id', 'id')->where('accion', 'Cédula General de Seguimiento')->orderBy('id', 'ASC');
            }  

            public function movimientosCedulaPRAS()
            {
                return $this->hasMany(Movimientos::class, 'accion_id', 'id')->where('accion', 'Cédula General PRAS')->orderBy('id', 'ASC');
            }

            public function movimientosCedulaRecomendacion()
            {
                return $this->hasMany(Movimientos::class, 'accion_id', 'id')->where('accion', 'Cédula General Recomendación')->orderBy('id', 'ASC');
            }

            public function movimientosCedulaAnalitica()
            {
                return $this->hasMany(Movimientos::class, 'accion_id', 'id')->where('accion', 'Cédula Analítica')->orderBy('id', 'ASC');
            }

            public function movimientosCedulaAnaliticaDesemp()
            {
                return $this->hasMany(Movimientos::class, 'accion_id', 'id')->where('accion', 'Cédula Analítica Desempeño')->orderBy('id', 'ASC');
            }

            public function tipo_auditoria()
            {
                return $this->hasOne(CatalogoTipoAuditoria::class, 'id', 'tipo_auditoria_id');
            }
            public function getPeriodoMesAttribute()
            {       
                          //ACF - 001/dian/001= 26/08/2024  
                $p_mes= substr($this->periodo_revision,0,-4);                                       
                return $p_mes;
            } 
            public function getPeriodoAnioAttribute()
            { 
                $p_anio= substr($this->periodo_revision,-4);                                                        
                return $p_anio;
            }            
           
            public function getTipoEntidadAmbitoAttribute(){

                $entidadF=EntidadFiscalizableIntra::where('PkCveEntFis',$this->entidad_fiscalizable_id)->first();
                if($entidadF->NivEntFis==3){
                    $entidad2=EntidadFiscalizableIntra::where('PkCveEntFis',$entidadF->FkCveEntFis)->first();
                    $entidad=EntidadFiscalizableIntra::where('PkCveEntFis',$entidad2->FkCveEntFis)->first();

                    return $entidad->NomEntFis;                    
                }

                if($entidadF->NivEntFis==2){
                    $entidad2=EntidadFiscalizableIntra::where('PkCveEntFis',$entidadF->FkCveEntFis)->first();

                    return $entidad2->NomEntFis;
                }

                if($entidadF->NivEntFis==1){
                    
                    return $entidadF->NomEntFis;
                }
            }

            public function informeprimeraetapa()
            {
                return $this->belongsTo(InformePrimeraEtapa::class, 'id', 'auditoria_id');
            }

            public function acuerdoconclusion()
            {
                return $this->belongsTo(AcuerdoConclusion::class, 'id', 'auditoria_id');
            }
            public function turnoui()
            {
                return $this->belongsTo(TurnoUI::class, 'id', 'auditoria_id');
            }
            public function turnooic()
            {
                return $this->belongsTo(TurnoOIC::class, 'id', 'auditoria_id');
            }
            public function turnoarchivo()
            {
                return $this->belongsTo(TurnoAcuseArchivo::class, 'id', 'auditoria_id');
            }
            public function archivotransferencia()
            {
                return $this->belongsTo(TurnoArchivoTransferencia::class, 'id','auditoria_id');
            }
        
}
