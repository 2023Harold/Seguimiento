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
        Schema::create('segsolicitudes_aclaracion_doc', function (Blueprint $table) {
            $table->id();
            $table->integer('consecutivo');
            $table->string('nombre_archivo',500);           
            $table->foreignId('solicitud_aclaracion_id')->constrained('segsolicitudes_aclaracion');            
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
        Schema::dropIfExists('segsolicitudes_aclaracion_doc');
    }
};
