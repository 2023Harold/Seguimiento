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
            $table->foreignId('usuario_creacion_id')->nullable()->constrained('segusers');
            $table->foreignId('usuario_modificacion_id')->nullable()->constrained('segusers');
            $table->string('fase_autorizacion', 50)->nullable();
            $table->string('nivel_autorizacion', 5)->nullable();
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
