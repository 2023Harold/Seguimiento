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
        Schema::table('segpliegos_observacion', function (Blueprint $table) {
            $table->enum('calificacion_sugerida', ['Atendida', 'No Atendida','Parcialmente Atendida'])->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('segpliegos_observacion', function (Blueprint $table) {
            //
        });
    }
};
