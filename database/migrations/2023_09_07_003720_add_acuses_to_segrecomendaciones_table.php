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
        Schema::table('segrecomendaciones', function (Blueprint $table) {
            $table->string('oficio_comprobante', 100)->nullable();
            $table->date('fecha_comprobante')->nullable();
            $table->string('oficio_acuse', 100)->nullable();
            $table->date('fecha_acuse')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('segrecomendaciones', function (Blueprint $table) {
            //
        });
    }
};
