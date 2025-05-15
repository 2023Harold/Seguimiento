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
        Schema::table('segacuerdo_conclusion', function (Blueprint $table) {
            $table->string('acuerdo_conclusion', 100)->nullable()->change();
            // $table->foreignId('auditoria_id')->constrained('segauditorias')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('segacuerdo_conclusion', function (Blueprint $table) {
            //
        });
    }
};
