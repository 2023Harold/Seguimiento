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
            $table->string('nombre_testigo1',100)->nullable();
            $table->string('cargo_testigo1',300)->nullable();
            $table->string('numero_identificacion_testigo1',100)->nullable();
            $table->string('nombre_testigo2',100)->nullable();
            $table->string('cargo_testigo2',300)->nullable();
            $table->string('numero_identificacion_testigo2',100)->nullable();
            $table->string('nombre_representante',100)->nullable();
            $table->string('cargo_representante1',300)->nullable();
            $table->string('numero_identificacion_representante',100)->nullable();

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
