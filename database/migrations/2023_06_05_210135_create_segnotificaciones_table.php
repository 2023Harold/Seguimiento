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
        Schema::create('segnotificaciones', function (Blueprint $table) {
            $table->id();
            $table->string('titulo', 200);
            $table->string('mensaje', 1000);
            $table->date('fecha_muestra_inicio');
            $table->date('fecha_muestra_fin')->nullable();
            $table->string('estatus');
            $table->foreignId('unidad_administrativa_id')->nullable();
            $table->foreignId('destinatario_id')->nullable()->constrained('segusers');
            $table->foreignId('usuario_creacion_id')->nullable()->constrained('segusers');
            $table->foreignId('usuario_actualizacion_id')->nullable()->constrained('segusers');
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
        Schema::dropIfExists('segnotificaciones');
    }
};
