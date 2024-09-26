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
        Schema::table('segturno_acuse_envio_archivo', function (Blueprint $table) {
           
            $table->string('legajos_tecnico_archivo', 100)->nullable();
            $table->string('fojas_tecnico_archivo',100)->nullable();
            $table->string('legajos_seg_archivo',100)->nullable();
            $table->string('fojas_seg_archivo',100)->nullable();
            $table->date('fecha_notificacion_archivo')->nullable();
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('segturno_acuse_envio_archivo', function (Blueprint $table) {
            //
        });
    }
};
