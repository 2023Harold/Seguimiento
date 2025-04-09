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
        Schema::create('segturno_archivo_trasferencia', function (Blueprint $table) {

            $table->id();          
            $table->string('inventario_transferencia',100);
            $table->date('fecha_transferencia')->nullable();
            $table->string('tiempo_resguardo', 100);
            $table->string('clave_topografica', 150);
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
        Schema::dropIfExists('segturno_archivo_trasferencia');
    }
};
