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
        Schema::create('segmovimientos', function (Blueprint $table) {
            $table->id();
            $table->string('tipo_movimiento', 200);
            $table->string('accion', 100);
            $table->unsignedInteger('accion_id');
            $table->unsignedInteger('firmante_id', 100)->nullable();
            $table->string('pdf', 100)->nullable();
            $table->string('estatus', 50)->nullable();
            $table->string('motivo_rechazo', 1000)->nullable();
            $table->unsignedInteger('usuario_asignado_id');
            $table->unsignedInteger('usuario_creacion_id');
            $table->unsignedInteger('usuario_actualizacion_id')->nullable();
            $table->foreign('firmante_id')->references('id')->on('segusers');
            $table->foreign('usuario_asignado_id')->references('id')->on('segusers');
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
        Schema::dropIfExists('segmovimientos');
    }
};
