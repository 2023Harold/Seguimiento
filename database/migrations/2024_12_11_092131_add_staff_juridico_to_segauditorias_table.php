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
        Schema::table('segauditorias', function (Blueprint $table) {
            $table->string('staff_asignada')->nullable();
            $table->unsignedBigInteger('staff_juridico_id')->nullable();
            $table->string('reasignacion_staff',2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('segauditorias', function (Blueprint $table) {
            //
        });
    }
};
