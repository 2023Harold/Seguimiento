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
        Schema::table('segcomparecencia', function (Blueprint $table) {
            $table->date('fecha_inicio_proceso')->nullable();
            $table->date('fecha_termino_proceso')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('segcomparecencia', function (Blueprint $table) {
            $table->dropColumns(['fecha_inicio_proceso','fecha_termino_proceso']);
        });
    }
};
