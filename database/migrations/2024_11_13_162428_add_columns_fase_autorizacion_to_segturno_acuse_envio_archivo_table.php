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
            $table->string('fase_autorizacion', 40)->nullable();
            $table->string('nivel_autorizacion', 100)->nullable();
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