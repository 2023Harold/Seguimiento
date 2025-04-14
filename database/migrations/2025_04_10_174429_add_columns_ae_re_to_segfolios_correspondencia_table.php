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
            $table->string('recomendaciones_extemp',10)->nullable();
            $table->string('acciones_extemp',10)->nullable();
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

        });
    }
};
