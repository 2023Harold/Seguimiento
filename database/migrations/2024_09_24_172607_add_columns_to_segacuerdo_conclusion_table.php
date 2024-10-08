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
        Schema::table('segacuerdo_conclusion', function (Blueprint $table) {
            $table->string('nombre_titular', 100)->nullable();
            $table->string('cargo_titular',100)->nullable();
            $table->string('domicilio',150)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('segacuerdo_conclusion', function (Blueprint $table) {
            //
        });
    }
};
