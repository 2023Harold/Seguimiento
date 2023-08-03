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
        Schema::create('segconstancias', function (Blueprint $table) {
            $table->id();
            $table->string('archivo_xml', 512)->nullable();
            $table->string('archivo_fir', 512)->nullable();
            $table->string('constancia_xml', 512)->nullable();
            $table->integer('id_proceso_xml')->nullable();
            $table->string('hash_xml', 512)->nullable();
            $table->string('constancia_pdf', 512)->nullable();
            $table->integer('id_proceso_pdf')->nullable();
            $table->string('hash_pdf', 512)->nullable();
            $table->string('accion', 512);
            $table->string('accion_campo')->nullable();
            $table->integer('accion_id')->nullable();
            $table->string('estatus', 50)->nullable();
            $table->string('motivo_rechazo', 3000)->nullable();
            $table->foreignId('usuario_creacion_id')->nullable()->constrained('segusers');
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
        Schema::dropIfExists('segconstancias');
    }
};
