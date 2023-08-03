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
        Schema::create('segauditorias', function (Blueprint $table) {
            $table->id();
            $table->string('numero_auditoria', 100);
            $table->integer('entidad_fiscalizable_id')->nullable();
            $table->string('entidad_fiscalizable', 500)->nullable();
            $table->string('tipo_entidad', 500)->nullable();
            $table->string('siglas_entidad', 500)->nullable();
            $table->integer('ejercicio')->nullable();
            $table->string('periodo_revision', 150)->nullable();
            $table->integer('tipo_auditoria_id')->nullable();
            $table->string('acto_fiscalizacion', 150)->nullable();           
            $table->string('informe_auditoria', 150)->nullable();
            $table->unsignedBigInteger('unidad_administrativa_registro')->nullable();   
            $table->unsignedBigInteger('usuario_creacion_id')->nullable(); 
            $table->unsignedBigInteger('usuario_actualizacion_id')->nullable();
            $table->string('registro_concluido')->nullable()->default('No');
            $table->string('constancia', 100)->nullable();
            $table->string('fase_autorizacion', 50)->nullable();
            $table->string('nivel_autorizacion', 5)->nullable();
            $table->unsignedBigInteger('lider_proyecto_id')->nullable(); 
            $table->string('direccion_asignada')->nullable(); 
            $table->unsignedBigInteger('direccion_asignada_id')->nullable(); 
            $table->string('reasignacion_direccion',2)->nullable();
            $table->string('asignacion_departamentos',2)->nullable();
            $table->foreign('direccion_asignada_id')->references('id')->on('segcatunidad_administrativas');  
            $table->foreign('unidad_administrativa_registro')->references('id')->on('segcatunidad_administrativas');  
            $table->foreign('lider_proyecto_id')->references('id')->on('segusers');  
            $table->foreign('usuario_creacion_id')->references('id')->on('segusers');  
            $table->foreign('usuario_actualizacion_id')->references('id')->on('segusers');  
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
        Schema::dropIfExists('segauditorias');
    }
};
