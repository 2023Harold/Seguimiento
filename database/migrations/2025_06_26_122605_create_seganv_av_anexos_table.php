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
        Schema::create('seganv_av_anexos', function (Blueprint $table) {
            $table->id();
            $table->integer('consecutivo');
            $table->string('nombre_firmante',500);
            $table->string('cargo_firmante',500);
            $table->string('administracion_firmante',500);
            $table->string('archivo',150);
            $table->string('nombre_archivo',500);
            $table->foreignId('anvav_id')->constrained('seganv_av');
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
        Schema::dropIfExists('seganv_av_anexos');
    }
};
