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
        Schema::create('segsolicitudes_acl_anexos', function (Blueprint $table) {
            $table->id();
            $table->integer('consecutivo')->nullable();
            $table->string('archivo',150);
            $table->string('nombre_archivo',500);
            $table->foreignId('solicitudaclaracion_id')->constrained('segsolicitudes_aclaracion'); 
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
        Schema::dropIfExists('segsolicitudes_acl_anexos');
    }
};
