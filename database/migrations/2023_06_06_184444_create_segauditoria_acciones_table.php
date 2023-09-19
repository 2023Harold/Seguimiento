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
        Schema::create('segauditoria_acciones', function (Blueprint $table) {
            $table->id();
            $table->integer('consecutivo')->nullable();
            $table->string('tipo', 100)->nullable();
            $table->unsignedBigInteger('segtipo_accion_id')->nullable();
            $table->string('numero')->nullable();
            $table->string('cedula', 100)->nullable();
            $table->text('accion')->nullable();
            $table->decimal('monto_aclarar',11,2)->nullable();
            $table->unsignedBigInteger('segauditoria_id')->nullable();
            $table->string('departamento_asignado')->nullable();
            $table->unsignedBigInteger('departamento_asignado_id')->nullable();
            $table->string('reasignacion_departamento',2)->nullable();
	        $table->string('lider_asignado')->nullable();
	        $table->unsignedBigInteger('lider_asignado_id')->nullable();
            $table->string('reasignacion_lider',2)->nullable();
            $table->unsignedBigInteger('lider_anterior_id')->nullable();
 	        $table->string('analista_asignado')->nullable();
            $table->unsignedBigInteger('analista_asignado_id')->nullable();
            $table->string('reasignacion_analista',2)->nullable();
            $table->unsignedBigInteger('analista_anterior_id')->nullable();
	        $table->string('evidencia_recomendacion', 150)->nullable();
            $table->string('tipo_recomendacion', 250)->nullable();
	        $table->string('tramo_control_recomendacion', 250)->nullable();
	        $table->date('fecha_termino_recomendacion')->nullable();
            $table->string('plazo_recomendacion',250)->nullable();
	        $table->string('normativa_infringida', 3000)->nullable();
	        $table->string('solventada', 2)->default('No');
            $table->unsignedBigInteger('usuario_creacion_id')->nullable();
            $table->unsignedBigInteger('usuario_actualizacion_id')->nullable();
	        $table->foreign('lider_asignado_id')->references('id')->on('segusers');
            $table->foreign('segtipo_accion_id')->references('id')->on('segcattipo_accion');
            $table->foreign('segauditoria_id')->references('id')->on('segauditorias');
            $table->foreign('departamento_asignado_id')->references('id')->on('segcatunidad_administrativas');
            $table->foreign('usuario_creacion_id')->references('id')->on('segusers');
            $table->foreign('usuario_actualizacion_id')->references('id')->on('segusers');
            $table->foreign('lider_anterior_id')->references('id')->on('segusers');
            $table->foreign('analista_asignado_id')->references('id')->on('segusers');
            $table->foreign('analista_anterior_id')->references('id')->on('segusers');
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
        Schema::dropIfExists('segauditoria_acciones');
    }
};
