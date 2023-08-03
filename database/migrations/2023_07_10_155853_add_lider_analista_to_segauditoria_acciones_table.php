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
        Schema::table('segauditoria_acciones', function (Blueprint $table) {
            $table->string('lider_asignado')->nullable();
            $table->unsignedBigInteger('lider_asignado_id')->nullable();            
            $table->string('reasignacion_lider',2)->nullable();
            $table->unsignedBigInteger('lider_anterior_id')->nullable();

            $table->string('analista_asignado')->nullable();            
            $table->unsignedBigInteger('analista_asignado_id')->nullable();
            $table->string('reasignacion_analista',2)->nullable();
            $table->unsignedBigInteger('analista_anterior_id')->nullable();

            $table->foreign('lider_asignado_id')->references('id')->on('segusers');  
            $table->foreign('lider_anterior_id')->references('id')->on('segusers');  
            $table->foreign('analista_asignado_id')->references('id')->on('segusers');  
            $table->foreign('analista_anterior_id')->references('id')->on('segusers');  


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('segauditoria_acciones', function (Blueprint $table) {
            //
        });
    }
};
