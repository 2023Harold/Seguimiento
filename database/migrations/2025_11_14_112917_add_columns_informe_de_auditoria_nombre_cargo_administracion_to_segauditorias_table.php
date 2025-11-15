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
        Schema::table('segauditorias', function (Blueprint $table) {
            $table->string('nombre_informe_au',500)->nullable();
            $table->string('cargo_informe_au',500)->nullable();
            $table->string('administracion_informe_au',500)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('segauditorias', function (Blueprint $table) {
            //
        });
    }
};
