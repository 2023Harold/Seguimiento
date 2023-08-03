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
        Schema::create('segcatunidad_administrativas', function (Blueprint $table) {
            $table->id();
            $table->string('descripcion', 100);
            $table->unsignedInteger('direccion_id')->nullable();
            $table->timestamps();
            $table->foreign('direccion_id')->references('id')->on('segcatunidad_administrativas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('segcatunidad_administrativas');
    }
};
