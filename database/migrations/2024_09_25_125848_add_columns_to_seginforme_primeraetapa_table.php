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
            $table->string('nombre_titular_informe', 100)->nullable();
            $table->string('cargo_titular_informe',100)->nullable();
            $table->string('domicilio_informe',150)->nullable();
            $table->string('numero_fojas',100)->nullable();
            $table->date('fecha_envio')->nullable();
            $table->string('acuse_notificacion',100)->nullable();
            $table->string('fecha_notificacion',100)->nullable();
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
