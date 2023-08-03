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
        Schema::create('segcattipos_auditorias', function (Blueprint $table) {
            $table->id();
            $table->string('Descripcion')->nullable();
            $table->string('Sigla')->nullable();
            $table->string('cumplimiento_financiero', 1)->nullable();
            $table->string('inversion_fisica', 1)->nullable();
            $table->string('desempenio_legalidad', 1)->nullable();
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
        Schema::dropIfExists('segcattipos_auditorias');
    }
};
