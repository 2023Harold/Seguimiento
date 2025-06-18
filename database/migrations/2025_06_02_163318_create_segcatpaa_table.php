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
        Schema::create('segcatpaa', function (Blueprint $table) {
            $table->id();
            $table->integer('ejercicio_fiscal',30);
            $table->date('fecha_paa')->nullable();
            $table->integer('paa',30);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('segcatpaa');
    }
};
