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
        Schema::table('seganv_av_anexos', function (Blueprint $table) {
            $table->string('of_notificacion',150)->nullable();
            $table->date('fecha_notificacion')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('seganv_av_anexos', function (Blueprint $table) {
            //
        });
    }
};
