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
        Schema::table('segauditoria_usuarios', function (Blueprint $table) {
            $table->integer('analista_id')->nullable();
            $table->string('estatus',100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('segadutoria_usuarios', function (Blueprint $table) {
            //
        });
    }
};
