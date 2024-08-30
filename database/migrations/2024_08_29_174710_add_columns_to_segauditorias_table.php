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
            //
            $table->string('numero_orden',150)->nullable();
            $table->integer('fojas_utiles')->nullable();

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
