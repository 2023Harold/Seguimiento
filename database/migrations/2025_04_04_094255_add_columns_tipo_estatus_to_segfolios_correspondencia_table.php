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
        Schema::table('segfolios_correspondencia', function (Blueprint $table) {
            $table->string('solicitudes',50)->nullable();
            $table->string('presentacion',50)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('segfolios_correspondencia', function (Blueprint $table) {
            //
        });
    }
};
