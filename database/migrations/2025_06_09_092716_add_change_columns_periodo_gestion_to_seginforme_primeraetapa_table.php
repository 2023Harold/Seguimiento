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
            $table->string('periodo_gestion', 500)->nullable();
            $table->string('nombre_titular_informe', 500)->nullable()->change();
            $table->string('cargo_titular_informe',500)->nullable()->change();
            $table->string('domicilio_informe',500)->nullable()->change();
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
