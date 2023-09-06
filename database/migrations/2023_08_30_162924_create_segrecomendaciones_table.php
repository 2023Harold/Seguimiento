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
        Schema::create('segrecomendaciones', function (Blueprint $table) {
            $table->id();           
            $table->integer('consecutivo')->nullable();
            $table->date('fecha_vencimiento');
            $table->date('fecha_compromiso');
            $table->string('nombre_responsable', 150);
            $table->string('cargo_responsable', 150);
            // oficio_contestacion',150
            $table->foreignId('departamento_responsable_id')->constrained('segcatunidad_administrativas');
            $table->foreignId('auditoria_id')->constrained('segauditorias'); 
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
        Schema::dropIfExists('segrecomendaciones');
    }
};
