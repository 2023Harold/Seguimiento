<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('segpras', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_titular_oic', 100);
            $table->string('oficio_remision');
            $table->date('fecha_acuse_oficio')->nullable();
            $table->string('numero_oficio');
            $table->date('fecha_elaboracion_oficio');
            $table->date('fecha_proxima_seguimiento')->nullable();
            $table->string('anexos', 2)->default('No');
            $table->string('copias_conocimiento', 2)->default('No');
            $table->string('notificacion_estrados', 5)->nullable();
            $table->string('calle', 100)->nullable();
            $table->string('numero_domicilio', 10)->nullable();
            $table->string('colonia', 100)->nullable();
            $table->string('municipio', 100)->nullable();
            $table->string('entidad_federativa', 100)->nullable();
            $table->string('codigo_postal', 5)->nullable();
            $table->string('fase_autorizacion', 40)->nullable();
            $table->string('motivo_rechazo', 1000)->nullable();
            $table->string('constancia_turno', 100)->nullable();
            $table->string('oficio_comprobante', 100)->nullable();
            $table->date('fecha_recepcion')->nullable();
            $table->string('oficio_acuse', 100)->nullable();
            $table->date('fecha_acuse')->nullable();
            $table->string('estatus_llenado', 20)->nullable();
            $table->string('conclusion_seguimientos', 15)->nullable();
            $table->integer('accion_id')->constrained('segauditoria_acciones');
            $table->integer('auditoria_id')->constrained('segauditorias');
            $table->integer('entidad_fiscalizable_id')->constrained('FISCatentidad_Fiscalizables');//donde
            $table->integer('usuario_creacion_id')->constrained('users');
            $table->integer('usuario_modificacion_id')->nullable()->constrained('users');
            $table->integer('usuario_firmante_id')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('segpras');
    }
};
