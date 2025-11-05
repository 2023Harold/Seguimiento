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
            $table->string('cp_2024',100)->nullable();
            $table->string('cp_2025',100)->nullable();
            $table->string('cp_ua2024',100)->nullable();
            $table->string('cp_ua2025',100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
