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
        Schema::table('seginforme_primeraetapa', function (Blueprint $table) {
            $table->string('nombre_titular_informe', 100);
            $table->string('cargo_titular_informe',100);
            $table->string('domicilio_informe',150);
            $table->string('numero_fojas',100)->nullable();
            $table->date('fecha_envio',100);
            $table->string('acuse_notificacion',100);
            $table->string('fecha_notificacion',100);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('seginforme_primeraetapa', function (Blueprint $table) {
            //
        });
    }
};