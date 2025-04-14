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
        Schema::table('segpliegos_observacion_contestacion', function (Blueprint $table) {
            $table->foreignId('foliocrr_id')->nullable()->constrained('segfolios_correspondencia');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('segpliegos_observacion_contestacion', function (Blueprint $table) {
            //
        });
    }
};
