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
        Schema::create('segequipos_trabajo', function (Blueprint $table) {
            $table->id();
            $table->string('equipo_name', 250)->nullable();
            $table->integer('consecutivo')->nullable();
            $table->integer('auditoria_id')->nullable();
            $table->string('departamento_encargado')->nullable();
	        $table->unsignedBigInteger('departamento_encargado_id')->nullable();
            $table->string('estatus', 10)->default('Activo');
            $table->foreignId('usuario_creacion_id')->nullable()->constrained('segusers');
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
        Schema::dropIfExists('segequipos_trabajo');
    }
};
