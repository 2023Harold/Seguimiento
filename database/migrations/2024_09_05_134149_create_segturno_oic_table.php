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
        Schema::create('segturno_oic', function (Blueprint $table) {
           
                $table->id();
                $table->string('numero_turno_oic', 100);
                $table->date('fecha_turno_oic')->nullable();
                $table->string('turno_oic',100);
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
        Schema::dropIfExists('segturno_oic');
    }
};
