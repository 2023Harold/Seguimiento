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
        Schema::create('segcomparecencia', function (Blueprint $table)
        {
            $table->id();
            $table->foreignId('auditoria_id')->constrained('segauditorias');
            $table->string('nombre_titular', 120);           
            $table->string('cargo_titular', 120);
            $table->string('oficio_comparecencia')->nullable();
            $table->date('fecha_comparecencia');
            $table->string('hora_comparecencia_inicio', 20);
            $table->string('hora_comparecencia_termino', 20)->nullable();
            $table->date('fecha_inicio_aclaracion');
            $table->date('fecha_termino_aclaracion');
            $table->string('notificacion_estrados', 1)->nullable();
            $table->string('calle', 100)->nullable();
            $table->string('numero_domicilio', 10)->nullable();
            $table->string('colonia', 100)->nullable();
            $table->string('codigo_postal', 5)->nullable();
            $table->string('municipio', 100)->nullable();
            $table->string('entidad_federativa', 100)->nullable();
            $table->string('anexos', 2)->default('No');
            $table->string('copias_conocimiento', 2)->default('No');
            $table->string('domicilio_notificacion', 200)->nullable();
            $table->string('constancia', 100)->nullable();
            $table->string('fase_autorizacion', 50)->nullable();
            $table->string('nivel_autorizacion', 5)->nullable();
            $table->string('oficio_recepcion', 512)->nullable();
            $table->date('fecha_recepcion')->nullable();
            $table->string('oficio_acuse', 100)->nullable();
            $table->date('fecha_acuse')->nullable();            
            $table->string('oficio_acta', 100)->nullable();
            $table->string('numero_acta', 50)->nullable();            
            $table->date('fecha_acta')->nullable();
            $table->string('oficio_acreditacion', 120)->nullable();
            $table->string('oficio_respuesta', 512)->nullable();
            $table->date('fecha_respuesta')->nullable();
            $table->string('cedula_general', 100)->nullable();
            $table->date('fecha_cedula')->nullable();
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
        Schema::dropIfExists('segcomparecencia');
    }
};
