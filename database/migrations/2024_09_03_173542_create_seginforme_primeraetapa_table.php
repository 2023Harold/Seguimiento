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
            $table->string('numero_informe', 100);
            $table->date('fecha_informe')->nullable();
            $table->string('informe',100);
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
        Schema::dropIfExists('seginfome_primeretapa');
    }
};
