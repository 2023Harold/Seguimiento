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
        Schema::create('segsolicitudes_aclaracion', function (Blueprint $table) {
            $table->id();            
            $table->string('oficio_atencion');    
	        $table->date('fecha_oficio_atencion');       
            $table->enum('cumple', ['Atendida', 'No Atendida','Parcialmente Atendida'])->nullable();
            $table->foreignId('accion_id')->constrained('segauditoria_acciones');
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
        Schema::dropIfExists('segsolicitudes_aclaracion');
    }
};
