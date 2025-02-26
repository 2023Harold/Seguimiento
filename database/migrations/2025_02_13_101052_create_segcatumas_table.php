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
        Schema::create('segcatumas', function (Blueprint $table) {
            $table->id();
            $table->string('ejercicio',150);
            $table->string('cant_por_dia', 150);
            $table->string('cant_multiplicada', 150);
            $table->date('fecha_publicacion');
            $table->date('fecha_vigencia');
            $table->string('texto', 255);
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
        Schema::dropIfExists('segcatumas');
    }
};
