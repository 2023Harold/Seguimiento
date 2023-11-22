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
        Schema::create('segrecomendaciones_anexos', function (Blueprint $table) {
            $table->id();
            $table->integer('consecutivo')->nullable();
            $table->string('archivo',150);           
            $table->string('nombre_archivo',500);            
            $table->foreignId('recomendacion_id')->constrained('segrecomendaciones'); 
            $table->foreignId('usuario_creacion_id')->constrained('segusers');
            $table->foreignId('usuario_modificacion_id')->nullable()->constrained('segusers');
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
        Schema::dropIfExists('segrecomendaciones_anexos');
    }
};
