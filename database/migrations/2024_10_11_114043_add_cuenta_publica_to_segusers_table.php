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
        Schema::table('segusers', function (Blueprint $table) {
            $table->string('cp_2023',100)->nullable();
            $table->string('cp_2022',100)->nullable();
            $table->string('cp_2021',100)->nullable();
            $table->string('cp_ua2023',100)->nullable();
            $table->string('cp_ua2022',100)->nullable();
            $table->string('cp_ua2021',100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('segusers', function (Blueprint $table) {
            //
        });
    }
};
