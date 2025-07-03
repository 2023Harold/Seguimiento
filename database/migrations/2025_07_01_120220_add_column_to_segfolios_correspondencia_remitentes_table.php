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
        Schema::table('segfolios_correspondencia_remitentes', function (Blueprint $table) {
            $table->string('administracion_remitente', 500)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('segfolios_correspondencia_remitentes', function (Blueprint $table) {
            //
        });
    }
};
