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
        Schema::table('segfolios_correspondencia', function (Blueprint $table) {
            $table->string('oficio_contestacion_general',150)->nullable();
            $table->date('fecha_oficio_contestacion')->nullable();
            $table->string('numero_oficio',250)->nullable();
            $table->string('nombre_remitente',500)->nullable();
            $table->string('cargo_remitente',500)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('segfolios_correspondencia', function (Blueprint $table) {
            //
        });
    }
};
