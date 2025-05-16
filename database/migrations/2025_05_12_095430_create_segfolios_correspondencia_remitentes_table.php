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
        Schema::create('segfolios_correspondencia_remitentes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('folio_id')->constrained('segfolios_correspondencia');
            $table->string('nombre_remitente',500)->nullable();
            $table->string('cargo_remitente',500)->nullable();
            $table->string('domicilio_remitente',500)->nullable();
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
        Schema::dropIfExists('segfolios_correspondencia_remitentes');
    }
};
