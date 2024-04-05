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
            $table->string('aprobar_cedana_analista',2)->nullable();
            $table->string('aprobar_cedana_lider',2)->nullable();
            $table->string('aprobar_cedana_jefe',2)->nullable();
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
            //
        });
    }
};
