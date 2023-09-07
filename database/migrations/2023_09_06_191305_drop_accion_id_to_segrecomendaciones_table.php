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
        Schema::table('segrecomendaciones', function (Blueprint $table) {
            $table->dropForeign('SEGRECOMENDACIONE_ACCIO_ID_FK');
            $table->dropColumn('accion_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('segrecomendaciones', function (Blueprint $table) {
            //
        });
    }
};
