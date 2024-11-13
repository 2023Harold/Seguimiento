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
        Schema::table('seginforme_primeraetapa', function (Blueprint $table) {
            $table->date('fecha_acuse_envio')->nullable();
            $table->string('acuse_envio', 100)->nullable();   
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('seginforme_primeraetapa', function (Blueprint $table) {
            //
        });
    }
};
