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
        Schema::create('segacuerdosvaloracion', function (Blueprint $table) {
            $table->id();
            $table->foreignId('folio_id')->constrained('segfolios_correspondencia');
            $table->foreignId('auditoria_id')->constrained('segauditorias');
            $table->string('tipo_doc',30);
            $table->string('numero_oficio',100)->nullable();
            $table->date('fecha_oficio')->nullable();
            $table->string('nombre_firmate',500);
            $table->string('cargo_firmate',500);
            $table->string('anexos',100)->nullable();
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
        Schema::dropIfExists('segacuerdosvaloracion');
    }
};
