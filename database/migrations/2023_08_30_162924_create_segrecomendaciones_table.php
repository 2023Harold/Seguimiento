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
            $table->string('nombre_responsable', 150);
            $table->string('cargo_responsable', 150);
            $table->foreignId('departamento_responsable_id')->constrained('segcatunidad_administrativas');
	        $table->text('analisis')->nullable();
            $table->string('fase_revision', 40)->nullable();
            $table->string('calificacion_sugerida',30)->nullable();
            $table->string('calificacion_atencion',30)->nullable();
	        $table->text('conclusion')->nullable();
	        $table->string('concluido',2)->default("No");
	        $table->string('nivel_autorizacion', 100)->nullable();
	        $table->string('fase_autorizacion', 40)->nullable();
            $table->string('constancia_autorizacion', 100)->nullable();
            $table->foreignId('auditoria_id')->constrained('segauditorias');
            $table->foreignId('accion_id')->nullable()->constrained('segauditoria_acciones');
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
