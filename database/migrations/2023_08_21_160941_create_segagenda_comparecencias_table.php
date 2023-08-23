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
        Schema::create('segagenda_comparecencias', function (Blueprint $table) {
                $table->id('id_comparecencia');
                $table->dateTime('fecha')->nullable();
                $table->string('hora_inicio', 20);
                $table->string('hora_fin', 20);
                $table->foreignId('usuario_creacion_id')->nullable();
                $table->foreignId('usuario_actualizacion_id')->nullable();
                
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('segagenda_comparecencias');
    }
};
