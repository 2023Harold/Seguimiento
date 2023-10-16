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
        Schema::create('segpliegos_observacion', function (Blueprint $table) {
            $table->id();
            $table->enum('calificacion_sugerida', ['Solventado', 'No Solventado','Solventado Parcialmente'])->nullable();
            $table->enum('calificacion_atencion', ['Solventado', 'No Solventado','Solventado Parcialmente'])->nullable();
            $table->decimal('monto_solventado',19,2)->nullable();
            $table->text('analisis')->nullable();
            $table->string('fase_revision', 40)->nullable();            
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
        Schema::dropIfExists('segpliegos_observacion');
    }
};
