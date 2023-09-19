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
        Schema::create('segrevisiones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('de_usuario_id')->constrained('segusers'); 
            $table->foreignId('para_usuario_id')->constrained('segusers'); 
            $table->text('comentario');
            $table->string('accion',50);
            $table->integer('accion_id');
            $table->string('estatus',30)->nullable();
            $table->string('notificacion_titular',2)->nullable();
            $table->string('notificacion_director',2)->nullable();
            $table->string('notificacion_jefe',2)->nullable();
            $table->string('notificacion_lider',2)->nullable();
            $table->string('notificacion_analista',2)->nullable();            
            $table->foreignId('id_revision')->nullable()->constrained('segrevisiones'); 
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
        Schema::dropIfExists('segrevisiones');
    }
};
