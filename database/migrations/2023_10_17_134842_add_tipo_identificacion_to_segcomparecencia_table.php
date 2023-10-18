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
        Schema::table('segcomparecencia', function (Blueprint $table) {
            $table->string('tipo_identificacion', 100)->nullable();
            $table->string('tipo_identificacion1', 100)->nullable();
            $table->string('tipo_identificacion2', 100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('segcomparecencia', function (Blueprint $table) {
            //
        });
    }
};
