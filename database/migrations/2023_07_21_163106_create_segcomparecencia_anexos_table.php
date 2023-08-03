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
        Schema::create('segcomparecencia_anexos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('comparecencia_id')->constrained('segcomparecencia');
            $table->integer('numero', 10)->nullable();
            $table->string('archivo', 100);
            $table->string('descripcion', 800);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('segcomparecencia_anexos');
    }
};
