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
        Schema::create('segfolios_correspondencia', function (Blueprint $table) {
            $table->id();
            $table->string('folio',100);
            $table->date('fecha_recepcion_oficialia');
            $table->date('fecha_recepcion_us');
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
        Schema::dropIfExists('segfolios_correspondencia');
    }
};
