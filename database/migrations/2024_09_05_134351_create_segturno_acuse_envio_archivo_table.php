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
        Schema::create('segturno_acuse_envio_archivo', function (Blueprint $table) {
            $table->id();
            $table->string('numero_turno_archivo', 100);
            $table->date('fecha_turno_archivo')->nullable();
            $table->string('turno_archivo',100);
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
        Schema::dropIfExists('segturno_acuse_envio_archivo');
    }
};
