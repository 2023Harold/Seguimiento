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
            $table->string('evidencia_recomendacion', 150)->nullable();
            $table->string('tipo_recomendacion', 250)->nullable();
            $table->string('tramo_control_recomendacion', 250)->nullable();
            $table->date('fecha_termino_recomendacion')->nullable();
            $table->string('plazo_recomendacion',250)->nullable();
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
            $table->dropColumn('evidencia_recomendacion');
            $table->dropColumn('tipo_recomendacion');
            $table->dropColumn('tramo_control_recomendacion');
            $table->dropColumn('fecha_termino_recomendacion');
            $table->dropColumn('plazo_recomendacion');
        });
    }
};
