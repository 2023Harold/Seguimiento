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
	        $table->string('constancia', 100)->nullable();
            $table->string('fase_autorizacion', 50)->nullable();
            $table->string('nivel_autorizacion', 5)->nullable();
            $table->string('concluido',2)->default('No');
            $table->decimal('monto_solventado',11,2)->nullable();
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
