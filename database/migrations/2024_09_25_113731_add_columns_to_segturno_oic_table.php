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
        Schema::table('segturno_oic', function (Blueprint $table) {
            $table->string('nombre_titular_oic', 100);
            $table->string('cargo_titular_oic',100);
            $table->string('domicilio_oic',150);
            $table->date('fecha_envio',100);
            $table->string('acuse_notificacion',100);
            $table->string('fecha_notificacion',100);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('segturno_oic', function (Blueprint $table) {
            //
        });
    }
};