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
        Schema::create('seganv_av', function (Blueprint $table) {
            $table->id();
            $table->foreignId('folio_id')->constrained('segfolios_correspondencia');
            $table->foreignId('auditoria_id')->constrained('segauditorias');
            $table->string('numero_expediente',100);
            $table->string('tipo_doc',30);
            $table->string('numero_oficio_ent',100)->nullable();
            $table->date('fecha_oficio_ent')->nullable();
            $table->string('nombre_informe_au',500);
            $table->string('cargo_informe_au',500);
            $table->string('administracion_informe_au',500);
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
        Schema::dropIfExists('seganv_av');
    }
};
