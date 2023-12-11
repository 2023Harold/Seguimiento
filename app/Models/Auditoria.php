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
                'entidad_fiscalizable_id',	
                'entidad_fiscalizable',
                'tipo_entidad',	
                'siglas_entidad',
                'ejercicio',
                'periodo_revision',	
                'tipo_auditoria_id',
                'acto_fiscalizacion',                
                'informe_auditoria',
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
                'created_at',
                'updated_at',
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
                return $this->hasMany(AuditoriaAccion::class, 'segauditoria_id', 'id')->orderBy('numero');
            }

            public function accionesall()
            {                       
                return $this->belongsTo(AuditoriaAccion::class, 'id','segauditoria_id');
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
                return $this->hasMany(AuditoriaAccion::class, 'segauditoria_id', 'id')->sum('monto_aclarar');
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
                return $this->belongsTo(User::class, 'lider_proyecto_id', 'id');
            }

            public function getDirectorasignadoAttribute()
            {
                return User::where('unidad_administrativa_id',$this->direccion_asignada_id)->first();
            } 
            
            public function getJefedepartamentoencargadoAttribute()
            {
                return User::where('unidad_administrativa_id',$this->departamento_encargado_id)->first();
            }

            public function totalrecomendacion()
            {
                return $this->hasMany(AuditoriaAccion::class, 'segauditoria_id', 'id')->where('segtipo_accion_id', 2);
            }

            public function totalpras()
            {
                return $this->hasMany(AuditoriaAccion::class, 'segauditoria_id', 'id')->where('segtipo_accion_id', 4);
            }

            public function totalsolacl()
            {
                return $this->hasMany(AuditoriaAccion::class, 'segauditoria_id', 'id')->where('segtipo_accion_id', 1);
            }
            
            public function totalpliegos()
            {
                return $this->hasMany(AuditoriaAccion::class, 'segauditoria_id', 'id')->where('segtipo_accion_id', 3);
            }

            public function totalsolventadorecomendacion()
            {
                return $this->hasMany(AuditoriaAccion::class, 'segauditoria_id', 'id')->where('segtipo_accion_id', 2)
                            ->whereHas('recomendaciones', function (Builder $query) {
                                $query->where('calificacion_sugerida','Atendida');
                            });
            }
           
            public function totalsolventadopras()
            {
                return $this->hasMany(AuditoriaAccion::class, 'segauditoria_id', 'id')->where('segtipo_accion_id', 4)
                            ->whereHas('pras', function (Builder $query) {
                                $query->whereNotNull('constancia_turno');
                            });
            }

           
            public function totalsolventadosolacl()
            {
                return $this->hasMany(AuditoriaAccion::class, 'segauditoria_id', 'id')->where('segtipo_accion_id', 1)
                            ->whereHas('solicitudesaclaracion', function (Builder $query) {
                                $query->where('calificacion_sugerida', 'Solventada');
                            });
            }
            
            public function totalsolventadopliegos()
            {
                return $this->hasMany(AuditoriaAccion::class, 'segauditoria_id', 'id')->where('segtipo_accion_id', 3)
                            ->whereHas('pliegosobservacion', function (Builder $query) {
                                $query->where('calificacion_sugerida', 'Solventado');
                            });
            }

            public function totalNOsolventadorecomendacion()
            {
                return $this->hasMany(AuditoriaAccion::class, 'segauditoria_id', 'id')->where('segtipo_accion_id', 2)
                            ->whereHas('recomendaciones', function (Builder $query) {
                                $query->whereNotIn('calificacion_sugerida',['Atendida']);
                            });
            }
           
            public function totalNOsolventadopras()
            {
                return $this->hasMany(AuditoriaAccion::class, 'segauditoria_id', 'id')->where('segtipo_accion_id', 4)
                            ->whereHas('pras', function (Builder $query) {
                                $query->whereNull('constancia_turno');
                            });
            }

           
            public function totalNOsolventadosolacl()
            {
                return $this->hasMany(AuditoriaAccion::class, 'segauditoria_id', 'id')->where('segtipo_accion_id', 1)
                            ->whereHas('solicitudesaclaracion', function (Builder $query) {
                                $query->whereNotIn('calificacion_sugerida', ['Solventada']);
                            });
            }
            
            public function totalNOsolventadopliegos()
            {
                return $this->hasMany(AuditoriaAccion::class, 'segauditoria_id', 'id')->where('segtipo_accion_id', 3)
                            ->whereHas('pliegosobservacion', function (Builder $query) {
                                $query->whereNotIn('calificacion_sugerida', ['Solventado']);
                            });
            }




            public function accionesrevisadaslider()
            {
                return $this->hasMany(AuditoriaAccion::class, 'segauditoria_id', 'id')->whereNotNull('revision_lider');
            }
            public function accionesrechazadaslider()
            {
                return $this->hasMany(AuditoriaAccion::class, 'segauditoria_id', 'id')->whereNotNull('revision_lider')->where('revision_lider','Rechazado');
            }
            public function accionesrevisadasjefe()
            {
                return $this->hasMany(AuditoriaAccion::class, 'segauditoria_id', 'id')->whereNotNull('revision_jefe');
            }
            public function accionesrechazadasjefe()
            {
                return $this->hasMany(AuditoriaAccion::class, 'segauditoria_id', 'id')->whereNotNull('revision_jefe')->where('revision_jefe','Rechazado');
            }
}