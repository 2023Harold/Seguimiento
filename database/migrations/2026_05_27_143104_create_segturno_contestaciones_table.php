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
        Schema::create('segturno_contestaciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('turno_id');
            $table->string('tipo_turno',100);
            $table->string('archivo_contestacion',100);
            $table->date('fecha_notificacion');
            $table->date('fecha_recepcion');
            $table->text('observaciones')->nullable();
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
        Schema::dropIfExists('segturno_contestaciones');
    }
};
