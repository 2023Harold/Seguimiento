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
        Schema::create('seginforme_primeraetapa', function (Blueprint $table) {
            $table->id();
            $table->string('numero_ordenauditoria', 100);
            $table->date('fecha_notificacion_oficio')->nullable();
            $table->string('numero_oficio_entro',100);
            $table->string('acta_reunion_resultados',);
            $table->date('fecha_notificación')->nullable();
            $table->string('informe_seguimiento',100);//string
            $table->string('fojas_utiles',100);
            $table->string('clave_accion_pliego',100);
            $table->foreignId('auditoria_id')->constrained('segauditorias');
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
        Schema::dropIfExists('seginforme_primeraetapa');
    }
};
