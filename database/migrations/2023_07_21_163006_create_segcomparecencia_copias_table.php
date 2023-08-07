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
        Schema::create('segcomparecencia_copias', function (Blueprint $table) {
            $table->id();
            //$table->foreignId('comparecencia_id')->constrained('segcomparecencia');
            $table->integer('numero')->nullable();
            $table->string('nombre', 75);
            $table->string('domicilio_notificacion', 2)->nullable();
            $table->string('calle', 100)->nullable();
            $table->string('numero_domicilio', 10)->nullable();
            $table->string('colonia', 100)->nullable();
            $table->string('municipio', 100)->nullable();
            $table->string('entidad_federativa', 100)->nullable();
            $table->string('codigo_postal', 5)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('segcomparecencia_copias');
    }
};
