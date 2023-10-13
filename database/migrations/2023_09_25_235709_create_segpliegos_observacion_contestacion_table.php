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
        Schema::create('segpliegos_observacion_contestacion', function (Blueprint $table) {
            $table->id();
            $table->integer('consecutivo')->nullable();
            $table->string('oficio_contestacion',150);
            $table->date('fecha_oficio_contestacion');
            $table->string('numero_oficio',250);
            $table->string('nombre_remitente',500);
            $table->string('cargo_remitente',500);
            $table->date('fecha_recepcion_oficialia');
            $table->integer('folio_correspondencia');
            $table->date('fecha_recepcion_seguimiento');
            $table->string('nombre_archivo',500)->nullable();
            $table->foreignId('pliegosobservacion_id')->constrained('segpliegos_observacion');
            $table->foreignId('usuario_creacion_id')->constrained('segusers');
            $table->foreignId('usuario_modificacion_id')->nullable()->constrained('segusers');
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
        Schema::dropIfExists('segpliegos_observacion_contestacion');
    }
};
