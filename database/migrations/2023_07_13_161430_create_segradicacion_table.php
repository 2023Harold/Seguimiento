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
        Schema::create('segradicacion', function (Blueprint $table) {
            $table->id();
            $table->foreignId('auditoria_id')->constrained('segauditorias');
	        $table->string('numero_expediente', 150)->nullable();
            $table->string('numero_acuerdo', 30)->nullable();
            $table->string('oficio_acuerdo', 100)->nullable();
            $table->date('fecha_oficio_acuerdo')->nullable();
            $table->string('oficio_designacion', 100)->nullable();
            $table->date('fecha_oficio_designacion')->nullable();
            $table->string('constancia', 100)->nullable();
            $table->string('fase_autorizacion', 50)->nullable();
            $table->string('nivel_autorizacion', 5)->nullable();
            $table->foreignId('usuario_creacion_id')->constrained('segusers');
            $table->foreignId('usuario_modificacion_id')->nullable()->constrained('segusers');
            $table->foreignId('usuario_firmante_id')->nullable()->constrained('segusers');
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
        Schema::dropIfExists('segradicacion');
    }
};
