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
        Schema::table('segradicacion', function (Blueprint $table) {
            $table->text('num_memo_recepcion_expediente')->nullable();
            $table->date('fecha_expediente_turnado')->nullable();
            $table->date('fecha_oficio_informe')->nullable();
            $table->date('fecha_notificacion')->nullable();
            $table->integer('plazo_maximo')->nullable();
            $table->date('calculo_fecha')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('segradicacion', function (Blueprint $table) {
            //
        });
    }
};
